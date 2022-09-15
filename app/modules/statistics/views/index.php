<div class="row justify-content-center row-card statistics">
  <!-- header Statistic -->
  <?php
  if ($header_area) {
  ?>
    <div class="col-sm-12">
      <div class="row">
        <?php
        foreach ($header_area as $key => $item) {
        ?>
          <div class="col-sm-6 col-lg-3 item">
            <div class="card _section-elem p-3">
              <div class="d-flex align-items-center flex-row-reverse">
                <span class="stamp stamp-md <?= $item['class']; ?> text-white">
                  <i class="<?= $item['icon']; ?>"></i>
                </span>
                <div class="d-flex order-lg-2 mr-auto">
                  <div class="ml-2 d-lg-block">
                    <small class="text-muted d-block"><?= $item['name']; ?></small>
                    <h4 class="m-0 number"><?= $item['value']; ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php
  }
  ?>


  <div class="statistics__projects w-100">
    <div class="statistics__projects--body _section-block">
      <div class="statistics__projects--header _section-block-header">
        <h2 class="statistics__projects--title _title ps-4 ms-3">
          Projects
        </h2>
      </div>
      <div class="statistics__projects--block project-block">
        <div class="project-block__body _scrollbar-styles">
          <div class="statistics__projects--info project-block__info ps-3 pe-3">
            <div class="project-block__param _id">
              ID
            </div>
            <div class="project-block__param _name pe-4">
              Name
            </div>
            <div class="project-block__param _rate ms-4 me-4">
              Rate per $1000
            </div>
            <div class="project-block__param _min-max ms-4 me-4">
              Min/max Order
            </div>
            <div class="project-block__param _descr ms-4 me-4">
              Description
            </div>
          </div>
          <ul class="statistics__projects--list project-block__list">
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  1
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Soft UI Shopify Version
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  $14,000
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  $14,000
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  2
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Progress Track
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  $3,000
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  $3,000
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  3
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Fix Platform Errors
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  Not Set
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  Not Set
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  4
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Launch new Mobile App
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  $20,600
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  $20,600
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  5
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Add the New Landing Page
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  $4,000
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  $4,000
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
            <li class="statistics__projects--item project-block__item p-3 pt-2 pb-2">
              <div class="project-block__item--body row align-items-center">
                <div class="project-block__item--id col-2">
                  6
                </div>
                <div class="project-block__item--name ps-3 pe-4 col">
                  Redesign Online Store
                </div>
                <div class="project-block__item--value _rate ms-4 me-4 col-1">
                  $2,000
                </div>
                <div class="project-block__item--value _min-max ms-4 me-4 col-1">
                  $2,000
                </div>
                <div class="project-block__item--detail ms-4 me-4 col-2">
                  <a href="#project-popup" class="project-block__item--btn _btn _btn-popup">
                    Details
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="project-block__popup _popup" id="project-popup">
            <div class="_popup-wrapper">
              <div class="project-block__popup--bg _popup-bg"></div>
              <div class="project-block__popup--body _popup-body">
                <button type="button" class="project-block__popup--close-btn _popup-close-btn">
                  ✕
                </button>
                <h2 class="project-block__popup--title _title text-center _popup-title">
                  TITLE
                </h2>
                <div class="project-block__popup--content">
                  <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente asperiores non repellendus neque nostrum delectus error tempora nam! Vel ea autem tempora optio ullam, blanditiis excepturi maiores distinctio mollitia cumque!
                  </p>
                  <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus similique esse dolores commodi quo, reprehenderit repudiandae laboriosam nemo dignissimos molestiae, eius totam error blanditiis placeat debitis! Doloremque pariatur nulla quia?
                  </p>
                  <ul>
                    <li>• Lorem ipsum dolor sit amet.</li>
                    <li>• Lorem ipsum dolor sit amet.</li>
                    <li>• Lorem ipsum dolor sit amet.</li>
                  </ul>
                  <a href="#" class="project-block__popup--btn btn-primary _large-btn d-block">
                    Button
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Area -->
  <div class="col-sm-12 charts mt-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?= lang("recent_orders") ?></h3>
      </div>
      <div class="row">
        <div class="col-sm-8">
          <div class="p-4 card">
            <div id="orders_chart_spline" style="height: 20rem;"></div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="p-4 card">
            <div id="orders_chart_pie" style="height: 20rem;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      Chart_template.chart_spline('#orders_chart_spline', <?= $chart_and_orders_area['chart_spline'] ?>);
      Chart_template.chart_pie('#orders_chart_pie', <?= $chart_and_orders_area['chart_pie'] ?>);
    });
  </script>

  <!-- Orders Logs -->
  <?php
  if ($chart_and_orders_area) {
  ?>
    <div class="col-sm-12">
      <div class="row">
        <?php
        foreach ($chart_and_orders_area['orders_statistics'] as $key => $item) {
        ?>
          <div class="col-sm-6 col-lg-3 item">
            <div class="card p-3">
              <div class="d-flex align-items-center flex-row-reverse">
                <span class="stamp stamp-md text-primary mt-0 mr-3">
                  <i class="<?= $item['icon']; ?>"></i>
                </span>
                <div class="d-flex order-lg-2 mr-auto">
                  <div class="ml-2 d-flex flex-column-reverse">
                    <h4 class="m-0 number"><?= $item['value']; ?></h4>
                    <small class="text-muted "><?= $item['name']; ?></small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php
  }
  ?>
</div>

<div class="statistics__chart mt-4" style="display: none;">
  <div class="statistics__chart--body _section-elem">
    <h2 class="statistics__chart--title _title mb-4 pb-2 ps-sm-4 pb-sm-4 ps-4 pb-4">
      Recent orders
    </h2>
    <div class="statistics__chart--block">
      <canvas width="1571" class="statistics__chart--canvas" id="statistics-chart" height="285" style="display: block; box-sizing: border-box; height: 285px; width: 1571px;"></canvas>
    </div>
    <div class="statistics__chart--legend chart-legend mt-4" id="legend-container">
      <ul class="chart-legend-list">
        <li class="chart-legend-li"><span class="chart-legend-box" style="--color:rgba(187, 57, 188, 1);"></span><span class="chart-legend-text">Completed</span></li>
        <li class="chart-legend-li _disabled"><span class="chart-legend-box" style="--color:rgba(244, 183, 64, 1);"></span><span class="chart-legend-text">Processing</span></li>
        <li class="chart-legend-li _disabled"><span class="chart-legend-box" style="--color:rgba(162, 107, 0, 1);"></span><span class="chart-legend-text">Pending</span></li>
        <li class="chart-legend-li _disabled"><span class="chart-legend-box" style="--color:rgba(0, 186, 136, 1);"></span><span class="chart-legend-text">In progress</span></li>
        <li class="chart-legend-li"><span class="chart-legend-box" style="--color:rgba(28, 150, 238, 1);"></span><span class="chart-legend-text">Partial</span></li>
        <li class="chart-legend-li _disabled"><span class="chart-legend-box" style="--color:rgba(255, 76, 156, 1);"></span><span class="chart-legend-text">Canceled</span></li>
        <li class="chart-legend-li _disabled"><span class="chart-legend-box" style="--color:rgba(195, 0, 82, 1);"></span><span class="chart-legend-text">Refunded</span></li>
      </ul>
    </div>
  </div>
</div>

<!-- Top best Sellers
<?php
// $this->load->view('top_bestsellers');
?> -->

<script type="text/javascript" src="<?php echo BASE; ?>assets/js/libs.min.js"></script>