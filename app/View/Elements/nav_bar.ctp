<nav id='sidebar'>
  <div class='logo'>F</div>
  <ul>
    <li>
      <a href='#'><i class="fa fa-graduation-cap"></i>
                    <button class="btn btn-default dropdown-toggle filter-button" type="button" data-toggle="dropdown">
                        <span class="dropdown-title pull-left" option-title="<?= __('Spécialité');?>"><?= __('Spécialité');?></span>
                        <span class="glyphicon glyphicon-chevron-down small dropdown-chevron"></span>
                    </button>
                    <ul id="department_id" class="dropdown-menu" role="menu" value="0">
                        <li role="presentation" value="0" class="dropdown-li"><a role="menuitem" href="#"><?= __('Toutes');?></a></li>
                <?php foreach ($departments as $key => $department):?>
                        <li role="presentation" value="<?= $key; ?>" class="dropdown-li"><a role="menuitem" href="#"><?= $department; ?></a></li>
                <?php endforeach;?>
                    </ul>
         </a>
    </li>
    <li>
      <a href='#'><i class="fa fa-briefcase"></i><span>Motif</span></a>
    </li>
    <li>
      <a href='#'><i class="fa fa-users"></i><span>Ecole</span></a>
    </li>
    <li>
      <a href='#'><i class="fa fa-clock-o"></i><span>Periode</span></a>
    </li>
  </ul>
</nav>