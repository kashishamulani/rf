<!DOCTYPE html>
<html>

<head>
    <title>Tax Invoice</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        background: #fff;
        padding: 10px;
        font-size: 11.5px;
    }

    .invoice-box {
        max-width: 850px;
        margin: auto;
        border: 1px solid #000;
    }

    .header {
        text-align: center;
        padding: 6px;
        border-bottom: 1px solid #000;
        font-size: 14px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="invoice-box">

        <!-- Header -->
        <div class="header">Tax Invoice</div>

        <!-- IRN Details -->
        @if(!empty($invoice->irn_number))
        <table>
            <tr>
                <td style="padding:4px 6px; border-bottom:1px solid #000;">
                    <strong>IRN:</strong> {{ $invoice->irn_number }}
                    &nbsp;&nbsp;
                    <strong>Ack No:</strong> {{ $invoice->ack_no ?? '—' }}
                    &nbsp;&nbsp;
                    <strong>Ack Date:</strong>
                    {{ !empty($invoice->ack_date) ? \Carbon\Carbon::parse($invoice->ack_date)->format('d-m-Y H:i') : '—' }}
                </td>
            </tr>
        </table>
        @endif

        <!-- Provider Info -->
        <table>
            <tr>
                <td style="width:70%; border-bottom:1px solid #000;  padding:4px 6px; vertical-align:top;">
                    <strong>Service Provider Name :</strong> E-Biz Technocrats Pvt. Ltd.<br>
                    <strong>Service Provider Address :</strong> C-8, 856 Govindpuri, Gwalior (M.P.)<br>
                    <strong>Service Provider GSTIN :</strong> 23AABCE6357M2Z1<br>
                    <strong>Service Provider PAN :</strong> AABCE6357M<br>
                    <strong>Service Provider State Name :</strong> Madhya Pradesh (23)<br>

                    <strong>Invoice No:</strong> {{ $invoice->invoice_number ?? '________' }}<br>
                    <strong>Vendor Code:</strong> 3546912<br>
                    <strong>Batch Code:</strong> {{ optional($invoice->batch)->batch_code ?? '________' }}<br>
                    <strong>PO/Wo NO:</strong> {{ optional($invoice->batch->po)->po_no ?? '________' }}<br>
                    <strong>Format/Business:</strong>

                    @php
                    $formats = $invoice->batch->assignments
                    ->map(function($assignment){
                    return $assignment->format->type ?? $assignment->business ?? 'N/A';
                    })
                    ->unique()
                    ->values();
                    @endphp

                    @if($formats->count() > 0)
                    {{ $formats->implode(' | ') }}
                    @else
                    ________
                    @endif<br>



                    <strong>NO. of Training Hours:</strong>
                    {{ optional($invoice->batch)->training_hours ?? '________' }}<br>
                </td>

                <td style="border-bottom:1px solid #000; padding:4px 6px; text-align:right; vertical-align:bottom;">
                    <strong>Invoice Date :</strong>
                    {{ !empty($invoice->invoice_date) ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') : '________' }}
                </td>
            </tr>
        </table>

        <!-- Recipient Info -->
        <table>
            <tr>
                <td style="border-bottom:1px solid #000; padding:4px 6px;">
                    <strong>Service Recipient Name : </strong> Reliance Foundation<br>
                    <strong>Service Recipient Address : </strong>9th Floor, Market Chamber IV, Nariman Point, Mumbai
                    Maharastra<br>
                    <strong>Service Recipient GSTIN :</strong>27AAFCR0111H1ZU<br>
                    <strong>Service Recipient PAN :</strong> AACFR0111H<br>
                </td>
            </tr>
        </table>

        <!-- SAC -->
        <table>
            <tr>
                <td style="border-bottom:1px solid #000; padding:3px 6px;">
                    <strong>SAC :</strong> 999293
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="border:1px solid #000; width:5%;">Sr.No</th>
                    <th style="border:1px solid #000;">Description of Services</th>
                    <th style="border:1px solid #000; width:10%;">Quantity</th>
                    <th style="border:1px solid #000; width:15%;">Per Candidate</th>
                    <th style="border:1px solid #000; width:18%;">Taxable Value (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                $srNo = 1;
                @endphp

                @php
                $total = 0;
                $srNo = 1;
                @endphp

                @foreach($invoice->poItems as $item)

                @php
                $rate = $item->poItem->value ?? 0;
                $qty = $item->qty ?? 0;
                $amount = $qty * $rate;
                $total += $amount;
                @endphp

                <tr>
                    <td class="center" style="border:1px solid #000;">{{ $srNo++ }}</td>
                    <td style="border:1px solid #000;">{{ $item->poItem->item }}</td>
                    <td class="center" style="border:1px solid #000;">{{ $qty }}</td>
                    <td class="right" style="border:1px solid #000;">{{ number_format($rate,0) }}</td>
                    <td class="right" style="border:1px solid #000;">{{ number_format($amount,0) }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>

        <!-- Service Period + Total -->
        <table>
            <tr>
                <td style="padding:3px 6px;margin-top:5px;margin-bottom:5px;">
                    Service Period

                    {{ optional($invoice->batch)->service_from
                ? \Carbon\Carbon::parse($invoice->batch->service_from)->format('d-m-Y')
                : '________' }}

                    to

                    {{ optional($invoice->batch)->service_to
                ? \Carbon\Carbon::parse($invoice->batch->service_to)->format('d-m-Y')
                : '________' }}
                </td>

                <td class="right" style="width:18%;">
                    {{ number_format($total,0) }}
                </td>
            </tr>
        </table>

        @php
        $providerState = '23';
        $supplyState = $invoice->supply_state ?? '27';
        $gstAmount = $total * 0.18;

        if ($providerState == $supplyState) {
        $cgst = $gstAmount / 2;
        $sgst = $gstAmount / 2;
        $igst = 0;
        } else {
        $cgst = 0;
        $sgst = 0;
        $igst = $gstAmount;
        }

        $grandTotal = $total + $gstAmount;
        @endphp

        <!-- GST -->
        <table>
            <tr>
                <td>CGST</td>
                <td class="right" style="width:20%;">{{ $cgst > 0 ? '9 %' : '' }}</td>
                <td class="right" style="width:18%;">{{ $cgst > 0 ? number_format($cgst,0) : '' }}</td>
            </tr>
            <tr>
                <td>SGST</td>
                <td class="right">{{ $sgst > 0 ? '9 %' : '' }}</td>
                <td class="right">{{ $sgst > 0 ? number_format($sgst,0) : '' }}</td>
            </tr>
            <tr>
                <td>IGST</td>
                <td class="right">{{ $igst > 0 ? '18 %' : '' }}</td>
                <td class="right">{{ $igst > 0 ? number_format($igst,0) : '' }}</td>
            </tr>
        </table>

        <!-- Grand Total -->
        <table>
            <tr>
                <td class="bold">
                    Total Invoice Value
                </td>
                <td class="right bold" style="width:18%;">
                    {{ number_format($grandTotal,0) }}
                </td>
            </tr>
        </table>

        @php
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $amountInWords = ucwords($f->format($grandTotal)) . " Rupees Only";
        @endphp

        <!-- Amount in Words -->
        <table>
            <tr>
                <td style="border-bottom:1px solid #000; padding:4px 6px;">
                    <strong>Amount in words:</strong> {{ $amountInWords }}<br>
                    <strong>Whether tax is payable under reverse charge:</strong> No
                </td>
            </tr>
        </table>

        <!-- Signature -->
        <table>
            <tr>
                <td style="width:60%;"></td>
                <td style="padding:40px 6px 6px 6px; text-align:right;">
                    For <strong>E-Biz Technocrats Pvt. Ltd.</strong><br><br>
                    Authorized Signatory
                </td>
            </tr>
        </table>

    </div>

</body>

</html>