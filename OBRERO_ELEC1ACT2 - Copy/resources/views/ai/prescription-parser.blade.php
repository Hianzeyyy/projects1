<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intelligent Prescription Parser (NLP)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="phx-module-bg min-h-screen py-10 px-4 md:px-8">
    <div class="mx-auto max-w-[96rem] grid gap-6 xl:grid-cols-2">
        <section class="phx-card">
            <div class="mb-4">
                <h1 class="text-2xl md:text-3xl font-extrabold text-violet-950">Intelligent Prescription Parser (NLP)</h1>
                <p class="text-violet-700 text-sm mt-2">Instead of manually typing every medicine from a doctor's note, use NLP to extract structured fields for Add to Cart.</p>
                <ul class="mt-3 space-y-1 text-xs text-violet-800">
                    <li><strong>The Process:</strong> Upload a photo or type raw text (e.g., Amoxicillin 500mg TDS for 7 days).</li>
                    <li><strong>AI Action:</strong> Extracts Drug Name, Dosage, and Duration, then auto-fills cart fields.</li>
                    <li><strong>Benefit:</strong> Reduces manual entry errors and speeds up transactions.</li>
                </ul>
            </div>

            <form id="ai-parser-form" class="space-y-4" data-parse-url="{{ route('ai.parser.parse') }}" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-semibold text-violet-900" for="raw_text">Doctor's Note</label>
                <textarea id="raw_text" name="raw_text" rows="6"
                    class="w-full rounded-xl border border-violet-200 bg-white/80 px-4 py-3 text-sm text-violet-950 focus:outline-none focus:ring-2 focus:ring-violet-400"
                    placeholder="Example: Amoxicillin 500mg TDS for 7 days"></textarea>

                <div>
                    <label class="block text-sm font-semibold text-violet-900" for="prescription_image">Or Upload Prescription Photo</label>
                    <input id="prescription_image" name="prescription_image" type="file" accept="image/png,image/jpeg,image/jpg,image/webp"
                        class="mt-1 w-full rounded-xl border border-violet-200 bg-white px-3 py-2 text-sm text-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-400" />
                </div>

                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-300 transition hover:bg-violet-800">
                    Parse Prescription
                </button>
            </form>

            <div class="mt-6">
                <h2 class="text-sm font-bold uppercase tracking-wider text-violet-800">Parsed Output</h2>
                <pre id="ai-parser-output" class="mt-2 min-h-28 rounded-xl bg-violet-950 p-4 text-xs text-violet-100 overflow-x-auto">No parsed data yet.</pre>
            </div>
        </section>

        <section class="phx-card" id="transaction-builder" data-store-url="{{ route('transactions.pharmacy.store') }}" data-receipt-url-base="{{ url('/transactions') }}">
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-violet-950">Pharmacy Transaction</h2>
                    <p class="text-violet-700 text-sm mt-2">Build an itemized sale or prescription transaction.</p>
                </div>
                <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-800">Pharmacist: {{ $pharmacistName ?? 'N/A' }}</span>
            </div>

            <form id="pharmacy-transaction-form" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wide text-violet-700 mb-1.5">Transaction Type</label>
                        <select name="transaction_type" required class="phx-input">
                            <option value="sale">Sale</option>
                            <option value="prescription">Prescription</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wide text-violet-700 mb-1.5">Payment Method</label>
                        <select name="payment_method" required class="phx-input">
                            <option value="Cash">Cash</option>
                            <option value="GCash">GCash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wide text-violet-700 mb-1.5">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" value="0" min="0" max="100" step="0.01" class="phx-input">
                    </div>
                </div>

                <div class="rounded-2xl border border-violet-200 bg-white/90 p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-violet-900">Add to Cart (Auto-filled)</h3>
                        <button type="button" id="tx-add-row" class="rounded-xl bg-violet-100 px-5 py-2 text-sm font-bold text-violet-800 hover:bg-violet-200">Add Row</button>
                    </div>

                    <div id="tx-rows" class="space-y-2 overflow-x-auto pb-1" data-inventory='@json($inventory)' data-medicines='@json($medicines)'></div>
                    <div class="mt-2 text-xs text-violet-700">
                        Need more choices?
                        <a href="/medicines" class="font-semibold underline hover:text-violet-900">Add Medicine</a>
                        and
                        <a href="/inventory" class="font-semibold underline hover:text-violet-900">Add Inventory Stock</a>.
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wide text-violet-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="3" class="phx-input" placeholder="Optional notes"></textarea>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-violet-50 px-5 py-4">
                    <span class="text-base font-semibold text-violet-800">Estimated Total</span>
                    <span id="tx-estimated-total" class="text-2xl font-extrabold text-violet-900">PHP 0.00</span>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="rounded-xl bg-violet-700 px-6 py-3.5 text-base font-semibold text-white hover:bg-violet-800">Save Transaction</button>
                    <a id="receipt-download-link" href="#" target="_blank" class="hidden rounded-xl bg-violet-100 px-6 py-3.5 text-base font-semibold text-violet-800 hover:bg-violet-200">Download Receipt</a>
                </div>
            </form>
        </section>
    </div>
</body>
</html>
