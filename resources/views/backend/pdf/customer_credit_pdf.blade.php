@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
                
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Credit Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Credit Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card" id="element-to-print">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h3>
                                        <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="logo" height="24"/> BJ's Shopping Mall
                                    </h3>
                                </div>
                                <hr>
 
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>BJ's Shopping Mall</strong>
                                            <br>
                                            Boston, MA
                                            <br>
                                            minhanh.nguyenquoc@gmail.com
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                    
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>SL</strong></td>
                                                    <td class="text-center"><strong>Customer Name</strong></td>
                                                    <td class="text-center"><strong>Invoice Number</strong></td>
                                                    <td class="text-center"><strong>Date</strong></td>
                                                    <td class="text-end"><strong>Due Amount</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $total_due = '0';
                                                @endphp
                                                <!-- 1 invoice co 1 hoac nhieu invoice_details -->
                                                <!-- with('invoice_detail') -->
                                                @foreach($allData as $key => $payment)
                                                <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                    <td class="text-center">{{ $payment['customer']['name'] }}</td>
                                                    <td class="text-center">#{{ $payment['invoice']['invoice_number'] }}</td>
                                                    <td class="text-center">{{ date('d-m-Y', strtotime($payment['invoice']['date'])) }}</td>
                                                    <td class="text-end">${{ $payment->due_amount }}</td>
                                                </tr>
                                                @php 
                                                    $total_due += $payment->due_amount;
                                                @endphp
                                                @endforeach

                                                <tr style="background-color: #ddd">
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Total Due Amount</strong></td>
                                                    <td class="no-line text-end"><h4 class="m-0">${{ $total_due }}</h4></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        @php
                                            $date = new Datetime('now', new DateTimeZone('America/New_York'));
                                        @endphp

                                        <i>Printing time: {{ $date->format('F j, Y, g:i a') }}</i>

                                        <div class="d-print-none" data-html2canvas-ignore="true">
                                            <div class="float-end">
                                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                <span class="btn btn-primary waves-effect waves-light ms-2" id="downloadBtn">Download</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<!-- Printing JS -->
<script type="text/javascript">
    $(document).on("click", "#downloadBtn", function(){
        var opt = {
            margin:       0,
            filename:     'report.pdf',
            html2canvas: { scale: 2, scrollY: 0},
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        var element = document.getElementById('element-to-print');
        html2pdf().set(opt).from(element).save();
    })
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    const data = {
        labels: [
            'Red',
            'Blue',
            'Yellow'
        ],

        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };
  
    const config = {
        type: 'pie',
        data: data,
    };
  
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
  
</script>

@endsection