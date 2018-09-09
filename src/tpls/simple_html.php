<?php
/**
 * lsys pagination
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @license    http://kohanaframework.org/license
 */ 
use function LSYS\Pagination\__;

if (!isset($this)) die('^_^');//防止直接访问
// Number of page links in the begin and end of whole range

$total_pages=$page->get_total_page();
$total_items=$page->get_total();
$current_page=$page->get_page();

$first_page=$current_page>1?1:false;
$last_page=$total_pages>$current_page?$total_pages:false;

$previous_page=$page->get_prev_page();
$next_page=$page->get_next_page();


// Beginning group of pages: $n1...$n2
$n1 = 1;
$n2 = min($count_out, $total_pages);
// Ending group of pages: $n7...$n8
$n7 = max(1, $total_pages - $count_out + 1);
$n8 = $total_pages;
// Middle group of pages: $n4...$n5
$n4 = max($n2 + 1, $current_page - $count_in);
$n5 = min($n7 - 1, $current_page + $count_in);
$use_middle = ($n5 >= $n4);
// Point $n3 between $n2 and $n4
$n3 = (int) (($n2 + $n4) / 2);
$use_n3 = ($use_middle && (($n4 - $n2) > 1));
// Point $n6 between $n5 and $n7
$n6 = (int) (($n5 + $n7) / 2);
$use_n6 = ($use_middle && (($n7 - $n5) > 1));
// Links to display as array(page => content)
$links = array();
// Generate links data in accordance with calculated numbers
for ($i = $n1; $i <= $n2; $i++)
{
	$links[$i] = $i;
}
if ($use_n3)
{
	$links[$n3] = '&hellip;';
}
for ($i = $n4; $i <= $n5; $i++)
{
	$links[$i] = $i;
}
if ($use_n6)
{
	$links[$n6] = '&hellip;';
}
for ($i = $n7; $i <= $n8; $i++)
{
	$links[$i] = $i;
}



?>
	<div>
		<i>
		<?php
 echo __("Total:")?>
		<?php
 echo $total_pages?>
		<?php
 echo __("page")?>
		,
		<?php
 echo __("Now:")?>
		<?php
 echo $current_page?>
		<?php
 echo __("page")?>
		</i>
		<p>
	<?php
 if ($first_page !== FALSE): ?>
		<a href="<?php
 echo $this->_url($first_page) ?>" rel="first"><?php
 echo __('First') ?></a>
	<?php
 else: ?>
		<span><?php
 echo __('First') ?></span>
	<?php
 endif ?>

	<?php
 if ($previous_page !== FALSE): ?>
		<a href="<?php
 echo $this->_url($previous_page) ?>" rel="prev"><?php
 echo __('Previous') ?></a>
	<?php
 else: ?>
		<span><?php
 echo __('Previous') ?></span>
	<?php
 endif ?>

	<?php
 foreach ($links as $number => $content): ?>

		<?php
 if ($number === $current_page): ?>
			<strong><?php
 echo $content ?></strong>
		<?php
 else: ?>
			<a href="<?php
 echo $this->_url($number) ?>"><?php
 echo $content ?></a>
		<?php
 endif ?>

	<?php
 endforeach ?>

	<?php
 if ($next_page !== FALSE): ?>
		<a href="<?php
 echo $this->_url($next_page) ?>" rel="next"><?php
 echo __('Next') ?></a>
	<?php
 else: ?>
		<span><?php
 echo __('Next') ?></span>
	<?php
 endif ?>

	<?php
 if ($last_page !== FALSE): ?>
		<a href="<?php
 echo $this->_url($last_page) ?>" rel="last"><?php
 echo __('Last') ?></a>
	<?php
 else: ?>
		<span><?php
 echo __('Last') ?></span>
	<?php
 endif ?>
	</p>
	</div>
