<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with("customer")
            ->where("branch_id", auth()->user()->branch_id)
            ->latest()
            ->get();

        $branchId = auth()->user()->branch_id;

        $today = Order::where("branch_id", $branchId)
            ->whereDate("created_at", today())
            ->count();

        $process = Order::where("branch_id", $branchId)
            ->where("status", "process")
            ->count();

        $done = Order::where("branch_id", $branchId)
            ->where("status", "done")
            ->count();

        return view(
            "orders.index",
            compact("orders", "today", "process", "done"),
        );
    }

    public function store(Request $request)
    {
        $customer = Customer::create([
            "name" => $request->name,
            "phone" => $request->phone,
        ]);

        $branch = Auth::user()->branch;

        $price = $request->weight * $branch->price_per_kg;

        $order = Order::create([
            "customer_id" => $customer->id,
            "branch_id" => Auth::user()->branch_id,
            "weight" => $request->weight,
            // "price" => $request->weight * 7000,
            "price" => $price,
            "status" => "process",
        ]);

        $this->sendWA(
            $customer->phone,
            "Halo {$customer->name}, laundry {$order->weight}kg sedang diproses.",
        );

        return redirect("/");
    }

    public function done($id)
    {
        $order = Order::with("customer")->findOrFail($id);

        $order->update([
            "status" => "done",
        ]);

        $this->sendWA(
            $order->customer->phone,
            "Halo {$order->customer->name}, laundry Anda sudah selesai dan siap diambil.",
        );

        return redirect("/");
    }

    private function sendWA($phone, $message)
    {
        Http::withHeaders([
            "Authorization" => "TOKEN_FONNTE_KAMU",
        ])
            ->asForm()
            ->post("https://api.fonnte.com/send", [
                "target" => $phone,
                "message" => $message,
            ]);
    }
}
