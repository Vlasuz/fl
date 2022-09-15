<div class="page-header">
  <h1 class="page-title">
    <?= lang('Your_account') ?>
  </h1>
</div>

<div class="row profile flex-nowrap align-items-start">
  <div class="col-md-6 full-mobile">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?= lang("basic_information") ?></h3>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <form class="form actionForm" action="<?= cn($module . "/ajax_update") ?>" data-redirect="<?= cn($module) ?>" method="POST">
          <div class="form-body">
            <div class="row">

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang("first_name") ?></label>
                  <input class="form-control square" name="first_name" type="text" value="<?= (!empty($user->first_name)) ? $user->first_name : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="userinput5"><?= lang("last_name") ?></label>
                  <input class="form-control square" name="last_name" type="text" value="<?= (!empty($user->last_name)) ? $user->last_name : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Email') ?></label>
                  <input class="form-control square" name="email" type="email" value="<?= (!empty($user->email)) ? $user->email : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Timezone') ?></label>
                  <select name="timezone" class="form-control square">
                    <?php $time_zones = tz_list();
                    if (!empty($time_zones)) {
                      foreach ($time_zones as $key => $time_zone) {
                    ?>
                        <option value="<?= $time_zone['zone'] ?>" <?= (!empty($user->timezone) && $user->timezone == $time_zone["zone"]) ? 'selected' : '' ?>><?= $time_zone['time'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Password') ?></label>
                  <input class="form-control square" name="password" type="password">
                  <small class="text-primary"><?= lang("note_if_you_dont_want_to_change_password_then_leave_these_password_fields_empty") ?></small>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Confirm_password') ?></label>
                  <input class="form-control square" name="re_password" type="password">
                </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12 form-actions">
                <div class="p-l-10 card-bt">
                  <button type="submit" class="btn btn-primary btn-min-width mr-1 mb-1 _large-btn"><?= lang('Save') ?></button>
                </div>
              </div>
            </div>
          </div>
          <div class="">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?= lang("more_informations") ?></h3>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <form class="form actionForm" action="<?= cn($module . "/ajax_update_more_infors") ?>" data-redirect="<?= cn($module) ?>" method="POST">
          <div class="form-body">
            <div class="row">
              <?php
              if (!empty($user->more_information)) {
                $infors     = $user->more_information;
                $website    = get_value($infors, "website");
                $phone      = get_value($infors, "phone");
                $skype_id   = get_value($infors, "skype_id");
                $what_asap  = get_value($infors, "what_asap");
                $address    = get_value($infors, "address");
              }
              ?>  
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="userinput5"><?= lang('Website') ?></label>
                  <input class="form-control square" name="website" type="text" value="<?= (!empty($website)) ? $website : '' ?>">
                </div>
              </div> 

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Phone') ?></label>
                  <input class="form-control square" name="phone" type="text" value="<?= (!empty($phone)) ? $phone : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Skype_id') ?></label>
                  <input class="form-control square"  name="skype_id"  type="text" value="<?= (!empty($skype_id)) ? $skype_id : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang("whatsapp_number") ?></label>
                  <input class="form-control square"  name="what_asap"  type="text" value="<?= (!empty($what_asap)) ? $what_asap : '' ?>">
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label for="projectinput5"><?= lang('Address') ?></label>
                  <input class="form-control square" name="address" type="text" value="<?= (!empty($address)) ? $address : '' ?>">
                  <small class="text-primary"><?= lang("note_if_you_dont_want_add_more_information_then_leave_these_informations_fields_empty") ?></small>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 form-actions left">
                <div class="p-l-10">
                  <button type="submit" class="btn btn-primary btn-min-width mr-1 mb-1"><?= lang("Save") ?></button>
                </div>
              </div>
            </div>
          </div>
          <div class="">
          </div>
        </form>
      </div>
    </div>
  </div>   -->

  <div class="w-100 d-flex flex-column-reverse">
    <div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= lang('your_api_key') ?></h3>
          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
        </div>
        <div class="card-body">
          <form class="form actionForm" action="<?= cn($module . "/ajax_update_api") ?>" data-redirect="<?= cn($module) ?>" method="POST">
            <div class="form-group">
              <label> <?= lang('Key') ?></label>
              <div class="input-group">
                <input type="text" name="api_key" class="form-control square" value="<?= (!empty($user->api_key)) ? $user->api_key : '' ?>">
              </div>
            </div>
            <div class="p-l-10 card-bt">
              <button type="submit" class="btn btn-primary btn-min-width mr-1 mb-1 _large-btn"><?= lang("Generate_new") ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div>
      <div class="card mb-2">
        <div class="card-header">
          <h3 class="card-title">Your profile info</h3>
          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
        </div>
        <div class="card-body">
          <form class="form actionForm" action="<?= cn($module . "/ajax_update_api") ?>" data-redirect="<?= cn($module) ?>" method="POST">
            <div class="profile__block--row-body row align-items-center justify-content-sm-start justify-content-center">
              <div class="profile__avatar col-auto col-sm-auto me-4 col-lg-2">
                <div class="profile__avatar--image">
                  <a href="#" class="profile__avatar--body">
                    <img src="http://127.0.0.1:5500/img/user/avatar.png" width="96" height="96" alt="Avatar" class="profile__avatar--img">
                    <span class="profile__avatar--text">
                      edit
                    </span>
                  </a>
                </div>
              </div>
              <div class="profile__info col-auto mt-4 mb-4">
                <h1 class="profile__info--name mb-0">
                  Mark Stevenson
                </h1>
                <span class="profile__info--email">
                  mail@mail.com
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>