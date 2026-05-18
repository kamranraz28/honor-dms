    <section class="content-header">
      <p style="visibility: hidden;">BC</p>
      <ol class="breadcrumb">
        <li><a href="{{ route('distributor.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('distributor.distributor') }}">Profile</a></li>
        <li><a href="{{ route('distributor.returnProduct') }}">Return</a></li>
        <li><a href="{{ route('distributor.returndProduct') }}">Direct Return</a></li>

        <li><a href="{{ route('distributor.dailyPurchaseApprove') }}">Purchase Approve</a></li>    
        
        <!-- <li><a href="{{ route('distributor.distributor') }}"><i class="fa fa-dashboard"></i> Profile</a></li>
        <li><a href="{{ route('distributor.dailySalesReport') }}">Sales Report</a></li>
        <li><a href="{{ route('distributor.dailyCampaignReport') }}">Campaign Report</a></li> -->

        
        <li><a href="{{route('distributor.purchase')}}">Purchase</a></li>
        <li><a href="{{ route('distributor.sale') }}">Sale</a></li>
        <li><a href="{{ route('distributor.dailyStockReport') }}">Stock Report</a></li>
        <li><a href="{{ route('distributor.dailyPurchaseReport') }}">Purchase Report</a></li>
        <li><a href="{{ route('distributor.dailySalesReport') }}">Sales Report</a></li>

        <li><a href="{{ route('distributor.dailyPurchaseReportV1') }}">Distributor Purchase Report</a></li>

        <li><a href="{{ route('distributor.dailySalesReportV1') }}">Distributor Sales Report</a></li>

        <li><a href="{{ route('distributor.dailyStockReportV1') }}">Distributor Stock Report</a></li>

        <li><a href="{{ route('distributor.dailyRtlStockReportV1') }}">Retailer Stock Report</a></li>


      </ol>
    </section>