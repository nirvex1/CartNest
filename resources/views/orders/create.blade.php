@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <div class="mx-auto max-w-2xl">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4795c]">Almost there</p>
        <h1 class="mt-2 font-display text-4xl font-bold tracking-[-0.05em] text-[#17221d]">Choose how to pay</h1>

        <div class="mt-7 rounded-3xl border border-[#e4e9e5] bg-white p-6 shadow-[0_16px_40px_rgba(31,73,53,.06)] sm:p-8">
            <div class="rounded-2xl bg-[#edf3ed] px-5 py-4 text-[#1f4935]">
                <div class="flex items-center justify-between gap-4">
                    <p class="font-semibold">Order total</p>
                    <p class="font-display text-2xl font-bold">Rs. {{ number_format($order->total_price, 2) }}</p>
                </div>
                <p class="mt-1 text-sm text-[#6b746f]">Order ID: #{{ $order->id }}</p>
            </div>

            <h2 class="mt-8 font-display text-xl font-bold text-[#17221d]">Select payment method</h2>

            <form method="POST" action="{{ route('payment.process', $order->id) }}" class="mt-4 space-y-3">
                @csrf

                <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-[#1f4935] bg-[#f5faf5] p-4 transition has-[:checked]:ring-2 has-[:checked]:ring-[#dceadf]">
                    <input type="radio" name="payment_method" value="esewa" class="h-4 w-4 accent-[#1f4935]" checked>
                    <span class="flex h-12 w-16 items-center justify-center overflow-hidden rounded-xl bg-white p-2 ring-1 ring-[#e4e9e5]">
                        <img src="https://blog.esewa.com.np/wp-content/uploads/2017/07/esewa-logo.png" alt="eSewa logo" class="max-h-full max-w-full object-contain">
                    </span>
                    <span>
                        <span class="block font-semibold text-[#17221d]">eSewa</span>
                        <span class="mt-1 block text-sm text-[#6b746f]">Pay securely with your eSewa wallet</span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-[#d7e1d9] p-4 transition hover:border-[#1f4935] has-[:checked]:border-[#1f4935] has-[:checked]:bg-[#f5faf5]">
                    <input type="radio" name="payment_method" value="khalti" class="h-4 w-4 accent-[#1f4935]">
                    <span class="flex h-12 w-16 items-center justify-center overflow-hidden rounded-xl bg-white p-2 ring-1 ring-[#e4e9e5]">
                        <img src="https://khalti.com/static/images/khalti-logo.png" alt="Khalti logo" class="max-h-full max-w-full object-contain">
                    </span>
                    <span>
                        <span class="block font-semibold text-[#17221d]">Khalti</span>
                        <span class="mt-1 block text-sm text-[#6b746f]">Pay securely with Khalti by IME</span>
                    </span>
                </label>

                <div class="mt-7 border-t border-[#e4e9e5] pt-6">
                    <p class="mb-4 text-sm leading-6 text-[#6b746f]">You’ll be redirected to the selected gateway to complete your payment.</p>
                    <button type="submit" class="button-lift w-full rounded-2xl bg-[#1f4935] py-3.5 text-base font-bold text-white">
                        Continue to payment
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-[#1f4935] underline decoration-[#e4795c] decoration-2 underline-offset-4">← Back to Orders</a>
        </div>
    </div>
@endsection
