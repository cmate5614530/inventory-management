@extends('layout.main')
@section('content')

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif
<div class="row">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="brand-text float-left mt-4">
          <h3>{{trans('file.welcome')}} <span>{{Auth::user()->name}}</span> </h3>
      </div>
    </div>
  </div>
</div>
<!-- Counts Section -->
<section class="dashboard-counts">
  
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          
          <ul class="nav nav-tabs mt-2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" href="#customer-sale" role="tab" data-toggle="tab">{{trans('file.Sale')}}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#customer-payment" role="tab" data-toggle="tab">{{trans('file.Payment')}}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#customer-quotation" role="tab" data-toggle="tab">{{trans('file.Quotation')}}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#customer-return" role="tab" data-toggle="tab">{{trans('file.Return')}}</a>
            </li>
          </ul>

          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade show active" id="customer-sale">
                <div class="table-responsive">
                  <table id="sale-table" class="table">
                    <thead>
                      <tr>
                        <th class="not-exported"></th>
                        <th>{{trans('file.date')}}</th>
                        <th>{{trans('file.reference')}}</th>
                        <th>{{trans('file.Biller')}}</th>
                        <th>{{trans('file.Warehouse')}}</th>
                        <th>{{trans('file.Sale Status')}}</th>
                        <th>{{trans('file.Payment Status')}}</th>
                        <th>{{trans('file.grand total')}}</th>
                        <th>{{trans('file.Paid')}}</th>
                        <th>{{trans('file.Due')}}</th>
                        <th>{{trans('file.action')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($lims_sale_data as $key => $sale)
                        <?php 
                            $coupon = \App\Coupon::find($sale->coupon_id); 
                            if($coupon)
                              $coupon_code = $coupon->code;
                            else
                              $coupon_code = null;

                            if($sale->sale_status == 1)
                              $status = trans('file.Completed');
                            elseif($sale->sale_status == 2)
                              $status = trans('file.Pending');
                            else
                              $status = trans('file.Draft');

                            $sale_note = preg_replace('/\s+/S', " ", $sale->sale_note);
                            $staff_note = preg_replace('/\s+/S', " ", $sale->staff_note);
                        ?>
                        
                      <tr data-sale='["{{date($general_setting->date_format, strtotime($sale->created_at->toDateString()))}}", "{{$sale->reference_no}}", "{{$status}}", "{{$sale->biller->name}}", "{{$sale->biller->company_name}}", "{{$sale->biller->email}}", "{{$sale->biller->phone_number}}", "{{$sale->biller->address}}", "{{$sale->biller->city}}", "{{$sale->customer->name}}", "{{$sale->customer->phone_number}}", "{{$sale->customer->address}}", "{{$sale->customer->city}}", "{{$sale->id}}", "{{$sale->total_tax}}", "{{$sale->total_discount}}", "{{$sale->total_price}}", "{{$sale->order_tax}}", "{{$sale->order_tax_rate}}", "{{$sale->order_discount}}", "{{$sale->shipping_cost}}", "{{$sale->grand_total}}", "{{$sale->paid_amount}}", "{{$sale_note}}", "{{$staff_note}}", "{{$sale->user->name}}", "{{$sale->user->email}}", "{{$sale->warehouse->name}}", "{{$coupon_code}}", "{{$sale->coupon_discount}}"]'>
                        <td>{{$key}}</td>
                        <td>{{ date($general_setting->date_format, strtotime($sale->created_at->toDateString())) }}</td>
                        <td>{{$sale->reference_no}}</td>
                        <td>{{$sale->biller->name}}</td>
                        <td>{{$sale->warehouse->name}}</td>
                        @if($sale->sale_status == 1)
                        <td><div class="badge badge-success">{{$status}}</div></td>
                        @elseif($sale->sale_status == 2)
                        <td><div class="badge badge-danger">{{$status}}</div></td>
                        @else
                        <td><div class="badge badge-warning">{{$status}}</div></td>
                        @endif
                        @if($sale->payment_status == 1)
                        <td><div class="badge badge-danger">{{trans('file.Pending')}}</div></td>
                        @elseif($sale->payment_status == 2)
                        <td><div class="badge badge-danger">{{trans('file.Due')}}</div></td>
                        @elseif($sale->payment_status == 3)
                        <td><div class="badge badge-success">{{trans('file.Partial')}}</div></td>
                        @else
                        <td><div class="badge badge-success">{{trans('file.Paid')}}</div></td>
                        @endif
                        <td>{{number_format($sale->grand_total, 2)}}</td>
                        <td>{{number_format($sale->paid_amount, 2)}}</td>
                        <td>{{number_format($sale->grand_total - $sale->paid_amount, 2)}}</td>
                        <td><button type="button" class="btn btn-info btn-sm sale-view-btn" title="{{trans('file.View')}}"><i class="dripicons-preview"></i></button></td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot class="tfoot active">
                      <tr>
                          <th></th>
                          <th>Total:</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>0.00</th>
                          <th>0.00</th>
                          <th>0.00</th>
                          <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="customer-payment">
                <div class="table-responsive mb-4">
                    <table id="payment-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="not-exported-payment"></th>
                                <th>{{trans('file.Date')}}</th>
                                <th>{{trans('file.Payment Reference')}}</th>
                                <th>{{trans('file.Sale Reference')}}</th>
                                <th>{{trans('file.Amount')}}</th>
                                <th>{{trans('file.Paid Method')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lims_payment_data as $key=>$payment)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{date($general_setting->date_format, strtotime($payment->created_at))}}</td>
                                    <td>{{$payment->payment_reference}}</td>
                                    <td>{{$payment->sale_reference}}</td>
                                    <td>{{$payment->amount}}</td>
                                    <td>{{$payment->paying_method}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="tfoot active">
                            <tr>
                                <th></th>
                                <th>Total:</th>
                                <th></th>
                                <th></th>
                                <th>0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="customer-quotation">
                <div class="table-responsive mb-4">
                    <table id="quotation-table" class="table quotation-list">
                        <thead>
                            <tr>
                                <th class="not-exported"></th>
                                <th>{{trans('file.Date')}}</th>
                                <th>{{trans('file.reference')}}</th>
                                <th>{{trans('file.Biller')}}</th>
                                <th>{{trans('file.customer')}}</th>
                                <th>{{trans('file.Supplier')}}</th>
                                <th>{{trans('file.Quotation Status')}}</th>
                                <th>{{trans('file.grand total')}}</th>
                                <th>{{trans('file.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lims_quotation_data as $key=>$quotation)
                            <?php
                                if($quotation->quotation_status == 1)
                                    $status = trans('file.Pending');
                                else
                                    $status = trans('file.Sent');
                            ?>
                            <tr class="quotation-link" data-quotation='["{{date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))}}", "{{$quotation->reference_no}}", "{{$status}}", "{{$quotation->biller->name}}", "{{$quotation->biller->company_name}}","{{$quotation->biller->email}}", "{{$quotation->biller->phone_number}}", "{{$quotation->biller->address}}", "{{$quotation->biller->city}}", "{{$quotation->customer->name}}", "{{$quotation->customer->phone_number}}", "{{$quotation->customer->address}}", "{{$quotation->customer->city}}", "{{$quotation->id}}", "{{$quotation->total_tax}}", "{{$quotation->total_discount}}", "{{$quotation->total_price}}", "{{$quotation->order_tax}}", "{{$quotation->order_tax_rate}}", "{{$quotation->order_discount}}", "{{$quotation->shipping_cost}}", "{{$quotation->grand_total}}", "{{$quotation->note}}", "{{$quotation->user->name}}", "{{$quotation->user->email}}"]'>
                                <td>{{$key}}</td>
                                <td>{{ date($general_setting->date_format, strtotime($quotation->created_at->toDateString())) . ' '. $quotation->created_at->toTimeString() }}</td>
                                <td>{{ $quotation->reference_no }}</td>
                                <td>{{ $quotation->biller->name }}</td>
                                <td>{{ $quotation->customer->name }}</td>
                                @if($quotation->supplier_id)
                                <td>{{ $quotation->supplier->name }}</td>
                                @else
                                <td>N/A</td>
                                @endif
                                @if($quotation->quotation_status == 1)
                                    <td><div class="badge badge-danger">{{$status}}</div></td>
                                @else
                                    <td><div class="badge badge-success">{{$status}}</div></td>
                                @endif
                                <td>{{ $quotation->grand_total }}</td>
                                <td><button type="button" class="btn btn-info btn-sm quotation-view-btn" title="{{trans('file.View')}}"><i class="dripicons-preview"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="tfoot active">
                            <th></th>
                            <th>{{trans('file.Total')}}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="customer-return">
                <div class="table-responsive mb-4">
                    <table id="return-table" class="table return-list">
                        <thead>
                            <tr>
                                <th class="not-exported"></th>
                                <th>{{trans('file.Date')}}</th>
                                <th>{{trans('file.reference')}}</th>
                                <th>{{trans('file.Biller')}}</th>
                                <th>{{trans('file.customer')}}</th>
                                <th>{{trans('file.Warehouse')}}</th>
                                <th>{{trans('file.grand total')}}</th>
                                <th class="not-exported">{{trans('file.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lims_return_data as $key=>$return)
                            <tr class="return-link" data-return='["{{date($general_setting->date_format, strtotime($return->created_at->toDateString()))}}", "{{$return->reference_no}}", "{{$return->warehouse->name}}", "{{$return->biller->name}}", "{{$return->biller->company_name}}","{{$return->biller->email}}", "{{$return->biller->phone_number}}", "{{$return->biller->address}}", "{{$return->biller->city}}", "{{$return->customer->name}}", "{{$return->customer->phone_number}}", "{{$return->customer->address}}", "{{$return->customer->city}}", "{{$return->id}}", "{{$return->total_tax}}", "{{$return->total_discount}}", "{{$return->total_price}}", "{{$return->order_tax}}", "{{$return->order_tax_rate}}", "{{$return->grand_total}}", "{{$return->return_note}}", "{{$return->staff_note}}", "{{$return->user->name}}", "{{$return->user->email}}"]'>
                                <td>{{$key}}</td>
                                <td>{{ date($general_setting->date_format, strtotime($return->created_at->toDateString())) . ' '. $return->created_at->toTimeString() }}</td>
                                <td>{{ $return->reference_no }}</td>
                                <td>{{ $return->biller->name }}</td>
                                <td>{{ $return->customer->name }}</td>
                                <td>{{$return->warehouse->name}}</td>
                                <td class="grand-total">{{ $return->grand_total }}</td>
                                <td><button type="button" class="btn btn-info btn-sm return-view-btn" title="{{trans('file.View')}}"><i class="dripicons-preview"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="tfoot active">
                            <th></th>
                            <th>{{trans('file.Total')}}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="sale-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-3">
                        <button id="sale-print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> {{trans('file.Print')}}</button>
                    </div>
                    <div class="col-md-6">
                        <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}</h3>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{trans('file.Sale Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="sale-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-sale-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>{{trans('file.Qty')}}</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Tax')}}</th>
                    <th>{{trans('file.Discount')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!-- <div id="sale-footer" class="modal-body"></div> -->
        </div>
    </div>
</div>

<div id="quotation-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="quotation-print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> {{trans('file.Print')}}</button>
                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}</h3>
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="col-md-12 text-center">
                    <i style="font-size: 15px;">{{trans('file.Quotation Details')}}</i>
                </div>
            </div>
        </div>
            <div id="quotation-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-quotation-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>Qty</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Tax')}}</th>
                    <th>{{trans('file.Discount')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!-- <div id="quotation-footer" class="modal-body"></div> -->
      </div>
    </div>
</div>

<div id="return-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
        <div class="row">
            <div class="col-md-3">
                <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="dripicons-print"></i> {{trans('file.Print')}}</button>
            </div>
            <div class="col-md-6">
                <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}</h3>
            </div>
            <div class="col-md-3">
                <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="col-md-12 text-center">
                <i style="font-size: 15px;">{{trans('file.Return Details')}}</i>
            </div>
        </div>
    </div>
            <div id="return-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-return-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>{{trans('file.Qty')}}</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Tax')}}</th>
                    <th>{{trans('file.Discount')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!-- <div id="return-footer" class="modal-body"></div> -->
      </div>
    </div>
</div>
      
<script type="text/javascript">
    $(document).on("click", ".sale-view-btn", function() {
        var sale = $(this).parent().parent().data('sale');
        saleDetails(sale);
    });

    $(document).on("click", ".quotation-view-btn", function(){
        var quotation = $(this).parent().parent().data('quotation');
        quotationDetails(quotation);
    });

    $(document).on("click", ".return-view-btn", function(){
        var returns = $(this).parent().parent().data('return');
        returnDetails(returns);
    });

    $(document).on("click", "#sale-print-btn", function(){
      var divToPrint=document.getElementById('sale-details');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", "#quotation-print-btn", function(){
      var divToPrint=document.getElementById('quotation-details');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", "#return-print-btn", function() {
      var divToPrint=document.getElementById('return-details');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},10);
    });

    function saleDetails(sale){
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+sale[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+sale[1]+'<br><strong>{{trans("file.Warehouse")}}: </strong>'+sale[27]+'<br><strong>{{trans("file.Sale Status")}}: </strong>'+sale[2]+'<br><br><div class="row"><div class="col-md-6"><strong>{{trans("file.From")}}:</strong><br>'+sale[3]+'<br>'+sale[4]+'<br>'+sale[5]+'<br>'+sale[6]+'<br>'+sale[7]+'<br>'+sale[8]+'</div><div class="col-md-6"><div class="float-right"><strong>{{trans("file.To")}}:</strong><br>'+sale[9]+'<br>'+sale[10]+'<br>'+sale[11]+'<br>'+sale[12]+'</div></div></div>';
        $.get('sales/product_sale/' + sale[13], function(data){
            $(".product-sale-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
            cols += '<td>' + sale[14] + '</td>';
            cols += '<td>' + sale[15] + '</td>';
            cols += '<td>' + sale[16] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + sale[17] + '(' + sale[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Discount")}}:</strong></td>';
            cols += '<td>' + sale[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
            if(sale[28]) {
                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.Coupon Discount")}} ['+sale[28]+']:</strong></td>';
                cols += '<td>' + sale[29] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            }

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + sale[20] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + sale[21] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Paid Amount")}}:</strong></td>';
            cols += '<td>' + sale[22] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Due")}}:</strong></td>';
            cols += '<td>' + parseFloat(sale[21] - sale[22]).toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-sale-list").append(newBody);
        });
        //var htmlfooter = '<p><strong>{{trans("file.Sale Note")}}:</strong> '+sale[23]+'</p><p><strong>{{trans("file.Staff Note")}}:</strong> '+sale[24];
        $('#sale-content').html(htmltext);
        //$('#sale-footer').html(htmlfooter);
        $('#sale-details').modal('show');
    }

    function quotationDetails(quotation){
        $('input[name="quotation_id"]').val(quotation[13]);
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+quotation[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+quotation[1]+'<br><strong>{{trans("file.Status")}}: </strong>'+quotation[2]+'<br><br><div class="row"><div class="col-md-6"><strong>{{trans("file.From")}}:</strong><br>'+quotation[3]+'<br>'+quotation[4]+'<br>'+quotation[5]+'<br>'+quotation[6]+'<br>'+quotation[7]+'<br>'+quotation[8]+'</div><div class="col-md-6"><div class="float-right"><strong>{{trans("file.To")}}:</strong><br>'+quotation[9]+'<br>'+quotation[10]+'<br>'+quotation[11]+'<br>'+quotation[12]+'</div></div></div>';
        $.get('quotations/product_quotation/' + quotation[13], function(data){
            $(".product-quotation-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
            cols += '<td>' + quotation[14] + '</td>';
            cols += '<td>' + quotation[15] + '</td>';
            cols += '<td>' + quotation[16] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + quotation[17] + '(' + quotation[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Discount")}}:</strong></td>';
            cols += '<td>' + quotation[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + quotation[20] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + quotation[21] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-quotation-list").append(newBody);
        });
        //var htmlfooter = '<p><strong>{{trans("file.Note")}}:</strong> '+quotation[22]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+quotation[23]+'<br>'+quotation[24];
        $('#quotation-content').html(htmltext);
        //$('#quotation-footer').html(htmlfooter);
        $('#quotation-details').modal('show');
    }

    function returnDetails(returns){
        $('input[name="return_id"]').val(returns[13]);
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+returns[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+returns[1]+'<br><strong>{{trans("file.Warehouse")}}: </strong>'+returns[2]+'<br><br><div class="row"><div class="col-md-6"><strong>{{trans("file.From")}}:</strong><br>'+returns[3]+'<br>'+returns[4]+'<br>'+returns[5]+'<br>'+returns[6]+'<br>'+returns[7]+'<br>'+returns[8]+'</div><div class="col-md-6"><div class="float-right"><strong>{{trans("file.To")}}:</strong><br>'+returns[9]+'<br>'+returns[10]+'<br>'+returns[11]+'<br>'+returns[12]+'</div></div></div>';
        $.get('return-sale/product_return/' + returns[13], function(data){
            $(".product-return-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                cols += '<td>' + (subtotal[index] / qty[index]) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
            cols += '<td>' + returns[14] + '</td>';
            cols += '<td>' + returns[15] + '</td>';
            cols += '<td>' + returns[16] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + returns[17] + '(' + returns[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + returns[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-return-list").append(newBody);
        });
        //var htmlfooter = '<p><strong>{{trans("file.Return Note")}}:</strong> '+returns[20]+'</p><p><strong>{{trans("file.Staff Note")}}:</strong> '+returns[21]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+returns[22]+'<br>'+returns[23];
        $('#return-content').html(htmltext);
        //$('#return-footer').html(htmlfooter);
        $('#return-details').modal('show');
    }

    $('#sale-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 10]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-sale)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_sale(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_sale(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-sale)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_sale(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_sale(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-sale)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_sale(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum_sale(dt, false);
                },
                footer:true
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            }
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum_sale(api, false);
        }
    } );

    function datatable_sum_sale(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.cells( rows, 9, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.column( 8, {page:'current'} ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.column( 9, {page:'current'} ).data().sum().toFixed(2));
        }
    }

    $('#payment-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': 0
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-payment)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_payment(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_payment(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_payment(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_payment(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_payment(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum_payment(dt, false);
                },
                footer:true
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            }
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum_payment(api, false);
        }
    } );

    function datatable_sum_payment(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 4 ).footer() ).html(dt_selector.column( 4, {page:'current'} ).data().sum().toFixed(2));
        }
    }

    $('#quotation-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 8]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_quotation(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_quotation(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_quotation(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_quotation(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_quotation(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum_quotation(dt, false);
                },
                footer:true
            },
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum_quotation(api, false);
        }
    } );

    function datatable_sum_quotation(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    $('#return-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 7]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_return(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_return(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_return(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum_return(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum_return(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum_return(dt, false);
                },
                footer:true
            },
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum_return(api, false);
        }
    } );

    function datatable_sum_return(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
    }
</script>
@endsection

