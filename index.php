<?php require_once('auth/enable.php'); ?>
<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="manifest.json">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/default.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>        
    </head>
    <body ng-app="GotItApp" ng-controller="ListController">
        <div class="header">
            <div class="container header-sticky">            
                <h1>Got_It!</h1>   
                <button ng-click="toggleMenu()" type="button" id="btnInsert" class="btn-insert">+</button>
            </div>        
        </div>
        <div id="main" class="main">
            <div ng-controller="SwipeCtrl" id="slider">
                <div ng-repeat="item in list track by $index" class="slide-wrapper">
                    <div id="{{$index}}" data-itemId="{{item.ID}}"class="slide">
                            {{item.name}}
                    </div> 
                </div>
            </div>
        </div>             
        <div id="menuAddItem" class="sub-menu">
            <div class="control-wrapper">
                <input ng-model="newItem" type="text" id="txtNewItem" class="form-control" placeholder="new item...">
                <button ng-click="insertNew()" type="button" class="btn btn-success">Save</button>
            </div>
        </div>
        <script src="js/app.js"></script>
    </body>    
</html>