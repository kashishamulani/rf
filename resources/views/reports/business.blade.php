@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:22px; font-weight:600; margin:0; color:#111827;">
            <i class="fa-solid fa-chart-line"
               style="background:linear-gradient(135deg,#6366f1,#ec4899);
                      -webkit-background-clip:text;
                      -webkit-text-fill-color:transparent;"></i>
            Business Report
        </h2>
    </div>

    {{-- FILTER --}}
    <form method="GET" style="margin-bottom:18px; display:flex; gap:10px; flex-wrap:wrap;">

        <input type="text" name="state" value="{{ request('state') }}"
            placeholder="State"
            style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">

        <input type="text" name="hr" value="{{ request('hr') }}"
            placeholder="HR Name"
            style="padding:8px 12px;border-radius:10px;border:1px solid #e5e7eb;">

        <button type="submit"
            style="padding:8px 16px;background:#6366f1;color:white;border:none;border-radius:10px;">
            Filter
        </button>

    </form>

    {{-- TOTAL CALC --}}
    @php
        $totalValue = 0;
        $totalPayment = 0;
    @endphp

    {{-- TABLE --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;
                      border-collapse:collapse;
                      background:rgba(255,255,255,0.8);
                      backdrop-filter:blur(16px);
                      border-radius:16px;
                      box-shadow:0 10px 30px rgba(0,0,0,0.08);">

            <thead>
                <tr style="background:linear-gradient(135deg,#eef2ff,#fdf2f8);
                           border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px;">#</th>
                    <th style="padding:14px;">State</th>
                    <th style="padding:14px;">HR</th>
                    <th style="padding:14px;">Assignment</th>
                    <th style="padding:14px;">Number</th>
                    <th style="padding:14px;">Batch</th>
                    <th style="padding:14px;">Invoice</th>
                    <th style="padding:14px;">Value</th>
                    <th style="padding:14px;">Payment</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)

                @php
                    $totalValue += $row->value ?? 0;
                    $totalPayment += $row->payment ?? 0;
                @endphp

                <tr style="border-bottom:1px solid #f1f5f9; text-align:center;">

                    <td style="padding:14px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:14px;">
                        {{ $row->state }}
                    </td>

                    <td style="padding:14px;">
                        {{ $row->hr_name }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        {{ $row->assignment_name }}
                    </td>

                    <td style="padding:14px;">
                        {{ $row->number }}
                    </td>

                    <td style="padding:14px;">
                        {{ $row->batch_code ?? '-' }}
                    </td>

                    <td style="padding:14px;">
                        {{ $row->invoice_number ?? '-' }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#3730a3;">
                        ₹{{ number_format($row->value ?? 0,2) }}
                    </td>

                    <td style="padding:14px; font-weight:600; color:#166534;">
                        ₹{{ number_format($row->payment ?? 0,2) }}
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="9" style="padding:16px; text-align:center; color:#9ca3af;">
                        No report data found.
                    </td>
                </tr>
                @endforelse

                {{-- TOTAL ROW --}}
                <tr style="background:#f9fafb; font-weight:700;">
                    <td colspan="7" style="padding:14px; text-align:right;">
                        TOTAL
                    </td>

                    <td style="padding:14px; color:#3730a3;">
                        ₹{{ number_format($totalValue,2) }}
                    </td>

                    <td style="padding:14px; color:#166534;">
                        ₹{{ number_format($totalPayment,2) }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
@endsection