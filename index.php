
<?
 
require_once('_config.php');  
include_once('inc.php');

mysql_select_db(DATABASE , $conn) 
							    
     or die("Не могу выбрать базу данных");                          																																						// Если пар-р указ-я опущен, исп-я посл-е откр-е соед-е.
        
mysql_query("SET NAMES UTF8");   

$day = 0;                 
if(isset($_GET['day']))  
 {
	$day = $_GET['day']; 
}
else  
{    
	$day = date("w");   
	if($day==0) 
		$day=7;
}

$page=-1;

echo "
<html>
	<head>
	<title></title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>	
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>	
	<link rel='stylesheet' type='text/css' href='main.css' />
	</head>
	
	<body><div>";

if ( (isset($_GET['RouteNum'])) and (!isset($_GET['StopID']))) //если выбран №маршрута, не НЕ выбран ОП
{
	$page=2;  
	print_header('Остановки - '.Lang0($_GET['Transport']).' '.$_GET['RouteNum']);   
	GetDaysList($day);  
	GetBack($page);     
	GetMarshrutStops($_GET['RouteNum'], $day, $_GET['Transport']);
}
else if ( (isset($_GET['RouteNum'])) and (isset($_GET['StopID'])) and (!isset($_GET['stopname'])))
{
	if($_GET['m']==2)
	{
		$page=7;
	}
	else if($_GET['m']==1)
	{
		$page=3;	
	}	
	// $lnk = "?StopID=".$_GET['StopID']."&m=3&day=$day&RouteNum=".$_GET['RouteNum']."&RouteType=".$_GET['RouteType']."&Transport=".$_GET['Transport']."&stopname=".UrlEncode(GetNameStop($_GET['StopID']));
	print_header('Расписание - '.Lang0($_GET['Transport']).' '.$_GET['RouteNum'].'<br>Ост. <a href="'.$lnk.'">&quot;'.GetNameStop($_GET['StopID']).'&quot; ('.$_GET['StopID'].')</a>');
	GetDaysList($day);
	GetBack($page);
	GetRaspList($_GET['RouteNum'], $_GET['StopID'], $day, UrlDecode($_GET['RouteType']), $_GET['Transport']);
	
}
else if ( (isset($_GET['RouteNum'])) and (isset($_GET['StopID'])) and (isset($_GET['stopname'])))
{
	if($_GET['m']==3)
	{
		$page=8;	
		
		print_header("Просмотр по остановке <b>".UrlDecode($_GET['stopname'])."</b> (".$_GET['StopID'].")");
		GetDaysList($day);
		GetBack($page);	
		/*		
		echo "<table border='1' cellpadding='4' cellspacing='0'><tr><td valign='top' align='center'>Ближайшие<br>рейсы</td><td  align='center'>Маршруты, проходящие<br>через остановку</td></tr><tr><td valign='top'>";
		GetNearestTimes($_GET['StopID'], $day);	
		echo "</td><td valign='top'>";	
		GetMarshrutsListStop($_GET['StopID'], $day);	
		echo "</td></tr></table>";
		*/
		
		GetMarshrutsListStop($_GET['StopID'], $day);
	}
}
else if ( (!isset($_GET['RouteNum'])) and (isset($_GET['StopID'])))
{
	if (!isset($_GET['m']))
	{
		if(!isset($_GET['w']))
		{
			$page=4;
			print_header('Поиск остановки');			
			GetDaysList($day);
			GetBack($page);
			
		echo "<form action='index.php' name='form1' method='get'>Введите первые буквы названия 
		<input name='w' style='width:100px;'>
		<input type='submit' name='submit' value=' OK '>
		<input name='StopID' type='hidden' value=''>
		<input name='day' type='hidden' value='$day'>
		</form><br>";			
			
			
			//GetStopsList($day);
		}
		else
		{
		
			$page=4;
			print_header('Поиск остановки');
			GetDaysList($day);
			GetBack($page);
			
		echo "<form action='index.php' name='form1' method='get'>Введите первые буквы названия или код 
		<input name='w' style='width:100px;'>
		<input type='submit' name='submit' value=' OK '>
		<input name='StopID' type='hidden' value=''>
		<input name='day' type='hidden' value='$day'>
		</form><br>";			
			
			GetStopsList1($_GET['w'], $day);		
		
		}
		
	}
	else
	{
		if($_GET['m']==1)
		{
			$page=6;		
			print_header("Просмотр по остановке <b>".UrlDecode($_GET['stopname'])."</b> (".$_GET['StopID'].")");
			GetDaysList($day);
			GetBack($page);	
			/*		
			echo "<table border='1' cellpadding='4' cellspacing='0'><tr><td valign='top' align='center'>Ближайшие<br>рейсы</td><td  align='center'>Маршруты, проходящие<br>через остановку</td></tr><tr><td valign='top'>";
			GetNearestTimes($_GET['StopID'], $day);	
			echo "</td><td valign='top'>";	
			GetMarshrutsListStop($_GET['StopID'], $day);	
			echo "</td></tr></table>";
			*/
			
			GetMarshrutsListStop($_GET['StopID'], $day);
		}	
	}
}
else if (( isset($_GET['stopname'])) and (!isset($_GET['m'])))
{
	$page=5;
	$stopname = UrlDecode($_GET['stopname']);
	print_header('Выбор остановки');	
	GetDaysList($day);
	GetBack($page);
//	echo "";
	GetStopsListNamestop($day,$stopname);
}
else if ( (isset($_GET['Transport'])) and (!isset($_GET['RouteNum']))and (!isset($_GET['StopID'])))
{
	if(isset($_GET['n']))
	{
		$page=2;
		print_header('Остановки - '.Lang0($_GET['Transport']).' '.$_GET['n']);		
		GetDaysList($day);
		GetBack($page);
		
		//$RouteNum = GetRouteNum($_GET['Transport'], $_GET['n']);
		GetMarshrutStops($_GET['n'], $day, $_GET['Transport']);		
	}	
	else if(isset($_GET['m']))
	{
		$page=1;
		print_header('Список маршрутов - '.Lang($_GET['Transport']));		
		GetDaysList($day);
		GetBack($page);	
		echo "<form action='index.php' name='form1' method='get'>Введите номер 
		<input name='n' style='width:50px;'>
		<input type='submit' name='submit' value=' OK '>
		<input name='Transport' type='hidden' value='".$_GET['Transport']."'>
		<input name='day' type='hidden' value='$day'>
		</form>";
		GetMarshrutsList($_GET['Transport'], $day);	
	}	
	else
	{
		$page=1;
		print_header('Список маршрутов - '.Lang($_GET['Transport']));		
		GetDaysList($day);
		GetBack($page);	
		echo "<form action='index.php' name='form1' method='get'>Введите номер 
		<input name='n' style='width:50px;'>
		<input type='submit' name='submit' value=' OK '>
		<input name='Transport' type='hidden' value='".$_GET['Transport']."'>
		<input name='day' type='hidden' value='$day'>
		</form>";				
		echo "<a href='?Transport=".$_GET['Transport']."&m=1&day=".$day."'>Список маршрутов</a>";
	}


}
else  
{
	$page=0;  
	GetDaysList($day);  
	GetTransportList($day);		 								
								 
								 
	echo "<br><a href='?StopID=&day=$day'>Поиск остановки</a>";
	echo "<br><br> <a href='http://www.minsktrans.by/'>на ГЛАВНУЮ</a>";
}

echo "	
	</div></body>
</html>";

mysql_close();



defined("_BBC_PAGE_NAME") ? _BBC_PAGE_NAME : define("_BBC_PAGE_NAME", "PDA-версия расписания транспорта");
define("_BBCLONE_DIR", "../counter/");
define("COUNTER", _BBCLONE_DIR."mark_page.php");
//if (is_readable(COUNTER)) 
include_once(COUNTER);

?>