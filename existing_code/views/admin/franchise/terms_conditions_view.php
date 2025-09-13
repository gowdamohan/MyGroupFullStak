<ul class="breadcrumb">
  <li class="active">Dashboard</li>
</ul>

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-3">
            <div class="widget widget-info widget-padding-sm" style="min-height: 90px;">
                <div class="widget-big-int plugin-clock">08<span>:</span>17</div>                            
                <div class="widget-subtitle plugin-date">Wednesday, October 12, 2022</div>           
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 unselectable">
            <h3>Terms And Condtions</h3>
            <?php echo $terms->content ?>
        </div>
    </div>
</div>

<style type="text/css">
    .unselectable {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #cc0000;
    }
</style>