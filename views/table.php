<?php
// vim: ft=htmlphp

/* @var $this PaavTable */
/* @var $models array */
/* @var $attrlabels array */
/* @var $pages CPagination */
?>
<?php $this->widget('PaavPager', array('pages'=>$pages)); ?>
<table class="paavTable">
  <thead>
    <tr>
      <?php foreach ($attrLabels as $name => $label): ?>
      <th><?php echo $this->createSortLink($name, $label); ?>
      <?php endforeach; ?>
      <?php // Last th is for the commands column ?>
      <th>
  <tbody>

    <?php foreach ($models as $model): ?>
    <tr>

      <?php foreach ($attrLabels as $name => $label): ?>
      <td><?php echo $model[$name]; ?>
      <?php endforeach; ?>

      <td class="paavTable-commands">

        <a href="<?php
            echo $this->getAbsUrlByModel($model, 'view', array(
              'id'=>$model->id
            ));
          ?>"><i class="icon-search"></i></a>

        <a href="<?php
            echo $this->getAbsUrlByModel($model, 'update', array(
              'id'=>$model->id
            ));
          ?>"><i class="icon-edit"></i></a>

        <a href="<?php
            echo $this->getAbsUrlByModel($model, 'delete', array(
              'id'=>$model->id
            ));
          ?>"><i class="icon-delete"></i></a>

    <?php endforeach; ?>
</table>

