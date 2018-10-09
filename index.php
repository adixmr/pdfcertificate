<?php

if(ctype_alnum($_REQUEST['event'])){ //checks if the event name is alphanumeric

$file = explode("\n",$_REQUEST['list']);  //explodes the list of names (by newline) into an array

foreach($file as &$values){
	file_get_contents('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/api.php?name='.urlencode(trim($values)).'&event='.urlencode($_REQUEST[event]).'&date='.urlencode($_REQUEST["date"]).'');
	//makes a request to our pdf generator api for each participant
}

$rootPath = realpath($_REQUEST[event]);
$zip = new ZipArchive();
$zip->open(''.$_REQUEST[event].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    if (!$file->isDir())
    {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

//Adds the pdfs to a zip file

header('location: ./'.$_REQUEST[event].'.zip'); //redirects to the zip file to be downloaded
 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>PDF Certificate Generator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>PDF Certificate Generator</h2>
  <form class="form-horizontal" method="POST" action="">
    <div class="form-group">
      <label class="control-label col-sm-2" for="event">Event Name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="event" placeholder="Enter event name" name="event">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="date">Date:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="date" placeholder="Enter date" name="date">
      </div>
    </div>    
    <div class="form-group">
      <label class="control-label col-sm-2" for="list">List of names:</label>
      <div class="col-sm-10">          
        <textarea class="form-control" rows="10" id="list" placeholder="First name
Second name
Third name
and so on..." name="list"></textarea>
      </div>
    </div>
    

    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>

