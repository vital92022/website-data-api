<html>
 <head>
  <title>Получение и анализ данных</title>
 </head>
 <body>
<?php echo '<div align="center"><h1>Сайт для автоматического получения и анализа данных</h1></div>';

$dat = date("d.m.y");
echo "Здравствуйте, сегодня $dat<br>";

$con = mysqli_connect("127.0.0.1","root","","json") or die("Error " . mysqli_error($con));

$url = "https://data.cdc.gov/resource/muzy-jte6.json";

$jsondata = file_get_contents($url);

$array = json_decode($jsondata, true);

foreach ($array as $row) {

if (isset($row["septicemia_a40_a41"]) and isset($row["diabetes_mellitus_e10_e14"]) and isset($row["alzheimer_disease_g30"]) and isset($row["influenza_and_pneumonia_j09_j18"]) and isset($row["malignant_neoplasms_c00_c97"])){

$sql = "INSERT INTO my_json(Data_As_Of, Jurisdiction_of_Occurrence  , MMWR_Year, MMWR_Week, Week_Ending_Date, All_Cause, Natural_Cause, Septicemia, Malignant_neoplasms, Diabetes_mellitus, Alzheimer_disease,  Influenza_and_pneumonia) VALUES('".$row["data_as_of"]."', '".$row["jurisdiction_of_occurrence"]."', '".$row["mmwryear"]."', '".$row["mmwrweek"]."', '".$row["week_ending_date"]."', '".$row["all_cause"]."', '".$row["natural_cause"]."', '".$row["septicemia_a40_a41"]."', '".$row["malignant_neoplasms_c00_c97"]."','".$row["diabetes_mellitus_e10_e14"]."','".$row["alzheimer_disease_g30"]."','".$row["influenza_and_pneumonia_j09_j18"]."')";
}
mysqli_query($con,$sql);
}

mysqli_close($con);
echo "Данные получены и занесены в Базу Данных<br>"
?>

<?php

$sort_list = array(
    'Data_As_Of_asc'   => '`Data_As_Of`',
    'Data_As_Of_desc'  => '`Data_As_Of` DESC',
    'Jurisdiction_of_Occurrence_asc'  => '`Jurisdiction_of_Occurrence`',
    'Jurisdiction_of_Occurrence_desc' => '`Jurisdiction_of_Occurrence` DESC',
    'MMWR_Year_asc'   => '`MMWR_Year`',
    'MMWR_Year_desc'  => '`MMWR_Year` DESC',
    'MMWR_Week_asc'   => '`MMWR_Week`',
    'MMWR_Week_desc'  => '`MMWR_Week` DESC',
    'Week_Ending_Date_asc'   => '`Week_Ending_Date`',
    'Week_Ending_Date_desc'  => '`Week_Ending_Date` DESC',
    'All_Cause_asc'  => '`All_Cause`',
    'All_Cause_desc' => '`All_Cause` DESC',
    'Natural_Cause_asc'  => '`Natural_Cause`',
    'Natural_Cause_desc' => '`Natural_Cause` DESC', 
    'Septicemia_asc'  => '`Septicemia`',
    'Septicemia_desc' => '`Septicemia` DESC', 
    'Malignant_neoplasms_asc'  => '`Malignant_neoplasms`',
    'Malignant_neoplasms_desc' => '`Malignant_neoplasms` DESC', 
    'Diabetes_mellitus_asc'  => '`Diabetes_mellitus`',
    'Diabetes_mellitus_desc' => '`Diabetes_mellitus` DESC',  
    'Alzheimer_disease_asc'  => '`Alzheimer_disease`',
    'Alzheimer_disease_desc' => '`Alzheimer_disease` DESC', 
    'Influenza_and_pneumonia_asc'  => '`Influenza_and_pneumonia`',
    'Influenza_and_pneumonia_desc' => '`Influenza_and_pneumonia` DESC',   
);

$sort = @$_GET['sort'];
if (array_key_exists($sort, $sort_list)) {
    $sort_sql = $sort_list[$sort];
} else {
    $sort_sql = reset($sort_list);
}

$dbh = new PDO('mysql:dbname=json;host=jjj', 'root', '');
$sth = $dbh->prepare("SELECT * FROM `my_json` ORDER BY {$sort_sql}");
$sth->execute();
$list = $sth->fetchAll(PDO::FETCH_ASSOC);

function sort_link_bar($title, $a, $b) {
    $sort = @$_GET['sort'];
 
    if ($sort == $a) {
        return '<a class="active" href="?sort=' . $b . '">' . $title . ' <i>↑</i></a>';
    } elseif ($sort == $b) {
        return '<a class="active" href="?sort=' . $a . '">' . $title . ' <i>↓</i></a>';  
    } else {
        return '<a href="?sort=' . $a . '">' . $title . '</a>';  
    }
}

?>

<div align="center"><h3>Визуализация полученных данных:</h3></div>

<div class="sort-bar">
    <div class="sort-bar-title">Сортировать по:</div> 
    <div class="sort-bar-list">
        <?php echo sort_link_bar('Data As Of', 'Data_As_Of_asc', 'Data_As_Of_desc'); ?>
        <?php echo sort_link_bar('Jurisdiction of Occurrence', 'Jurisdiction_of_Occurrence_asc', 'Jurisdiction_of_Occurrence_desc'); ?>
        <?php echo sort_link_bar('MMWR Year', 'MMWR_Year_asc', 'MMWR_Year_desc'); ?>
        <?php echo sort_link_bar('MMWR Week', 'MMWR_Week_asc', 'MMWR_Week_desc'); ?>
        <?php echo sort_link_bar('Week Ending Date', 'Week_Ending_Date_asc', 'Week_Ending_Date_desc'); ?>
        <?php echo sort_link_bar('All Cause', 'All_Cause_asc', 'All_Cause_desc'); ?>
        <?php echo sort_link_bar('Natural Cause', 'Natural_Cause_asc', 'Natural_Cause_desc'); ?>
        <?php echo sort_link_bar('Septicemia', 'Septicemia_asc', 'Septicemia_desc'); ?>
        <?php echo sort_link_bar('Malignant neoplasms', 'Malignant_neoplasms_asc', 'Malignant_neoplasms_desc'); ?>
        <?php echo sort_link_bar('Diabetes mellitus', 'Diabetes_mellitus_asc', 'Diabetes_mellitus_desc'); ?>
        <?php echo sort_link_bar('Alzheimer disease', 'Alzheimer_disease_asc', 'Alzheimer_disease_desc'); ?>
        <?php echo sort_link_bar('Influenza and pneumonia', 'Influenza_and_pneumonia_asc', 'Influenza_and_pneumonia_desc'); ?>
    </div> 
 </div> 
<br>

<div class="scroll-table">
    <table>
        <thead>
            <tr>
                <th>Data As Of</th>
                <th>Jurisdiction of Occurrence</th>
                <th>MMWR Year</th>
                <th>MMWR Week</th>
                <th>Week Ending Date</th>
                <th>All Cause</th>
                <th>Natural Cause</th>
                <th>Septicemia</th>
                <th>Malignant neoplasms</th>
                <th>Diabetes mellitus</th>
                <th>Alzheimer disease</th>
                <th>Influenza and pneumonia</th>
            </tr>
        </thead>
    </table>
    <div class="scroll-table-body">
        <table>
            <tbody>
            <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo $row['Data_As_Of']; ?></td>
                    <td><?php echo $row['Jurisdiction_of_Occurrence']; ?></td>
                    <td><?php echo $row['MMWR_Year']; ?></td>
                    <td><?php echo $row['MMWR_Week']; ?></td>
                    <td><?php echo $row['Week_Ending_Date']; ?></td>
                    <td><?php echo $row['All_Cause']; ?> </td>
                    <td><?php echo $row['Natural_Cause']; ?></td>
                    <td><?php echo $row['Septicemia']; ?></td>
                    <td><?php echo $row['Malignant_neoplasms']; ?></td>
                    <td><?php echo $row['Diabetes_mellitus']; ?></td>
                    <td><?php echo $row['Alzheimer_disease']; ?></td>
                    <td><?php echo $row['Influenza_and_pneumonia']; ?> </td>
                </tr>
            <?php endforeach; ?>    
            </tbody>
        </table>

<style type="text/css">
.scroll-table-body {
    height: 430px;
    overflow-x:auto;
    margin-top: 0px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.scroll-table table {
    width:100%;
    table-layout: fixed;
    border: none;
}
.scroll-table thead th {
    font-weight: bold;
    text-align: left;
    border: none;
    padding: 10px 15px;
    background: #f9a34b;
    font-size: 14px;
}
.scroll-table tbody td {
    text-align: left;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    padding: 10px 15px;
    font-size: 14px;
    vertical-align: top;
}
.scroll-table tbody tr:nth-child(even){
    background: #f3f3f3;
}

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}

a {
   font-size: 16px; /* Размер шрифта */
    font-weight: bold; /* Жирное начертание */
    color: black; /* Цвет ссылки */
}


</style>

 </body>

</html>