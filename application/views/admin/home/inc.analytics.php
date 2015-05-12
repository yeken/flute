<?
$ga = new gapi('andres.dicamillo@gmail.com','eqysgcsmaswtyyze');
$profile_id = 52899059;

$filter = 'medium==referral && referralPath != /';

/***  // << add '/' to uncomment
$date_start = '2011-11-01';
$date_end = '2011-11-13';
//**/$date_start = $date_end = null;

$ga->requestReportData(
    $profile_id,
    array('source','referralPath'),//what field you are looking for
    array('pageviews','visits'),//what metric you want to calculate
    '-visits',//sort order, prefix - means descending
    $filter,//filter query
    $date_start,//yyyy-mm-dd or null
    $date_end,//yyyy-mm-dd or null
    1,//offset lookup
    100);//max result
?>
<div class="panel">
    <table>
        <tr>
          <th>Referral URL</th>
          <th>Pageviews</th>
          <th>Visits</th>
        </tr>
        <? 
		foreach($ga->getResults() as $result){
        ?>
        <tr>
          <td><?=$result->getSource() . $result->getReferralPath() ?></td>
          <td><?=$result->getPageviews() ?></td>
          <td><?=$result->getVisits() ?></td>
        </tr>
        <?
        }
        ?>
    </table>
	<?
    $ga->requestReportData($profile_id ,array('browser','browserVersion'),array('pageviews','visits'));
    
    foreach($ga->getResults() as $result)
    {
		echo '<strong>'.$result.'</strong><br />';
		echo 'Pageviews: ' . $result->getPageviews() . ' ';
		echo 'Visits: ' . $result->getVisits() . '<br />';
    }
    
    echo '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
    ?>  
</div>