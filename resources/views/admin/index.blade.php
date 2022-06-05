<!-- Page Content -->
@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        @php
            $buying_total = App\Models\Purchase::where('status', '1')
            ->whereBetween('date', [now()->subDays(30), now()->endOfDay()])->sum('buying_price');
            
            $buying_total_last_period = App\Models\Purchase::where('status', '1')
            ->whereBetween('date', [now()->subDays(61), now()->subDays(31)])->sum('buying_price');
            
            if ($buying_total_last_period != 0) {
                $buying_change = 100 * (($buying_total - $buying_total_last_period) / ($buying_total_last_period));
            } else {
                $buying_change = 0;
            }



            
            $invoices = App\Models\Invoice::where('status', '1')
            ->whereBetween('date', [now()->subDays(30), now()->endOfDay()])->get();

            $selling_total = 0;
            
            foreach($invoices as $key => $item) {
                $selling_total += $item['payment']['total_amount'];
            }

            $last_period_invoices = App\Models\Invoice::where('status', '1')
            ->whereBetween('date', [now()->subDays(61), now()->subDays(31)])->get();

            $selling_total_last_period = 0;
            foreach($last_period_invoices as $key => $item) {
                $selling_total_last_period += $item['payment']['total_amount'];
            }

            if ($selling_total_last_period != 0) {
                $selling_change = 100 * (($selling_total - $selling_total_last_period) / ($selling_total_last_period));
            } else {
                $selling_change = 0;
            }



            $profit = $selling_total - $buying_total;

            $profit_last_period = $selling_total_last_period - $buying_total_last_period;

            if ($profit_last_period != 0) {
                $profit_change = 100 * (($profit - $profit_last_period) / ($profit_last_period));
            } else {
                $profit_change = 0;
            }



            $customers = App\Models\Customer::whereBetween('created_at', [now()->subDays(30), now()->endOfDay()])->count();
            $customers_last_period = App\Models\Customer::whereBetween('created_at', [now()->subDays(61), now()->subDays(31)])->count();
            if ($customers_last_period != 0) {
                $customer_change = 100 * (($customers - $customers_last_period) / ($customers_last_period));
            } else {
                $customer_change = 0;
            }


        @endphp


        <div class="row">
            <h4 class="card-title mb-4">Statistics From Last 30 Days</h4>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Sales</p>
                                <h4 class="mb-2">${{ $selling_total }}</h4>
                                @if($selling_total_last_period != 0 && $selling_change > 0) 
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>{{ $selling_change }}%</span>from previous period</p>
                                @elseif($selling_total_last_period != 0 && $selling_change < 0) 
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>{{ $selling_change }}%</span>from previous period</p>
                                @elseif($selling_total_last_period == 0)
                                <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2">unknow</span>from previous period</p>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="mdi mdi-currency-usd font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                            
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Purchases</p>
                                <h4 class="mb-2">${{ $buying_total }}</h4>
                                @if($buying_total_last_period != 0 && $buying_change > 0) 
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>{{ $buying_change }}%</span>from previous period</p>
                                @elseif($buying_total_last_period != 0 && $buying_change < 0) 
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>{{ $buying_change }}%</span>from previous period</p>
                                @elseif($buying_total_last_period == 0)
                                <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2">unknow</span>from previous period</p>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-shopping-cart-2-line font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Profit</p>
                                <h4 class="mb-2">${{ $profit }}</h4>
                                @if($profit_last_period != 0 && $profit_change > 0) 
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>{{ $profit_change }}%</span>from previous period</p>
                                @elseif($profit_last_period != 0 && $profit_change < 0) 
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>{{ $profit_change }}%</span>from previous period</p>
                                @elseif($profit_last_period == 0)
                                <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2">unknow</span>from previous period</p>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-line-chart-fill font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">New Customers</p>
                                <h4 class="mb-2">{{ $customers }}</h4>
                                @if($customers_last_period != 0 && $customer_change > 0) 
                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>{{ $customer_change }}%</span>from previous period</p>
                                @elseif($customers_last_period != 0 && $customer_change < 0) 
                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>{{ $customer_change }}%</span>from previous period</p>
                                @elseif($customers_last_period == 0)
                                <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2">unknow</span>from previous period</p>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>  
                                </span>
                            </div>
                        </div>                                              
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Inventory Management System</h4>

                        @php
                            $products = App\Models\Product::orderBy('id', 'DESC')->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th>Supplier Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th width="5%">Stock Current</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach($products as $key => $item)
                                    <tr>
                                        <td><h6 class="mb-0">{{ $key+1 }}</h6></td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {{ $item['supplier']['name'] }}
                                        </td>
                                        <td>
                                            {{ $item['category']['name'] }}
                                        </td>
                                        <td>
                                            {{ $item['unit']['name'] }}
                                        </td>
                                        <td class="text-center"> {{ $item->quantity }}</td>
                                    </tr>
                                    @endforeach
                                    <!-- end foreach -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    
</div>

@endsection