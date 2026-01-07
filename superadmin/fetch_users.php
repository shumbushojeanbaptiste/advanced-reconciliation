<?php
include('../include/initialize.php');
$limit = '6';
$page = 1;
if($_POST['page'] > 1)
{
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
}
else
{
  $start = 0;
}

$query = "
SELECT * FROM tbl_admins 
";

if($_POST['query'] != '')
{
  $query .= '
  WHERE firstName LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR lastName LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR email LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR phone LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR address LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR company_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR reg_date LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY id ASC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '';
if($total_data > 0)
{
  foreach($result as $fetch_us)
  {
    if($i == 3 || $j == 0 || $j == $sql_us->rowCount()) {
        $output .='<div class="row-fluid">';
    }
    $output .= '
        <div class="span6">
            <div class="media user">
                <a class="media-avatar pull-left" href="#">
                    <img src="../../images/user.png">
                </a>
                <div class="media-body">
                    <h3 class="media-title">'.$fetch_us['firstName'].' '.$fetch_us['lastName'].'
                    </h3>
                    <p>
                        <small class="muted">'.$fetch_us['phone'].'</small></p>
                    <p>
                        <small class="muted">'.$fetch_us['email'].'</small></p>
                    <div class="media-option btn-group shaded-icon">
                        <button class="btn btn-small">
                            <i class="icon-envelope"></i>
                        </button>
                        <button class="btn btn-small">
                            <i class="icon-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    ';
    if($i == 3 || $j == 0 || $j == $sql_us->rowCount()) {
$output .= '</div>';
    $i = 0;
    }
        $i+=1;
    }
    }
else
{
  $output .= '
    <div class="row-fluid">
        <div class="span6">
            No User Available
        </div>
    </div>
  ';
}

$output .= '
<br />
<div class="pagination pagination-centered">
    <ul>
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 4)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id >= $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
    </ul>
</div>
';

echo $output;

?>