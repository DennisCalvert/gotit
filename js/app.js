var app = angular.module('GotItApp', [])

app.controller('ListController', ['$scope', 'ListService', function ($scope, ListService) {

    $scope.list = ListService.get();

    $scope.removeItem = function (index) {
        $scope.list.splice(index, 1);
    }

    $scope.setTouchIndex = function (index) {
        $scope.touchIndex = index;
    }

    $scope.insertNew = function () {
        $scope.list = ListService.post($scope.newItem);
        $scope.newItem = "";
        $scope.toggleMenu();
    };

    $scope.deleteItem = function (index) {
        $scope.list = ListService.del(index);
    }

    $scope.toggleMenu = function () {
        var txtInput = document.getElementById('txtNewItem'),
            menu = document.getElementById('menuAddItem'),
            main = document.getElementById('main');
        if (!menu.classList.contains('slide-in')) {
            txtInput.focus();
            main.style.display = 'none';
        } else {
            txtInput.blur();
            main.style.display = 'block';
        }
        menu.classList.toggle('slide-in');
    };

} ]);

app.controller('SwipeCtrl', ['$scope', function ($scope) {

    var swipe = {

        slideWidth: document.body.clientWidth,
        touchstartx: undefined,
        touchmovex: undefined,
        movex: 0,
        longTouch: undefined,

        init: function () {
            this.bindUIEvents();
        },

        bindUIEvents: function () {
            var slider = document.getElementById("slider"),
                self = this;
            slider.addEventListener("touchstart", function (e) { self.start(e) });
            slider.addEventListener("touchmove", function (e) { self.move(e) });
            slider.addEventListener("touchend", function (e) { self.end(e) });
        },

        start: function (event) {
            if (!event.target.classList.contains('slide')) {
                return;
            }

            event.target.classList.remove('isSliding');
            event.target.classList.add('isTouched');

            this.longTouch = false;
            setTimeout(function () {
                window.slider.longTouch = true;
            }, 250);

            this.touchstartx = event.touches[0].pageX;            
        },

        move: function (event) {
            if (!event.target.classList.contains('slide')) {
                return;
            }
            this.touchmovex = event.touches[0].pageX;

            this.movex = this.touchstartx - this.touchmovex;
            
            event.target.style.transform = 'translate3d(-' + this.movex + 'px,0,0)';
        },

        end: function (event) {
            if (!event.target.classList.contains('slide')) {
                return;
            }

            var absMove = Math.abs(this.slideWidth - this.movex);

            event.target.classList.add('isSliding');

            if (this.movex > (this.slideWidth / 2)) {
                event.target.style.transform = 'translate3d(-100%,0,0)';
                event.target.parentNode.classList.add('fold');
                $scope.list = ListService.del(index);
            } else {
                event.target.style.transform = 'translate3d(0,0,0)';
            }
            event.target.classList.remove('isTouched');
            this.movex = 0;
        }
    };
    swipe.init();
} ]);

app.factory('ListService', ['$http', function ($http) {

    return {

        get: function () {
            return localStorage.getItem('list').split(",");
        },

        post: function (itemName) {
            var list = localStorage.getItem('list').split(",");
            list.push(itemName);
            localStorage.setItem('list', list);
            return localStorage.getItem('list').split(",");
        },

        del: function (index) {
            var listArray = localStorage.getItem('list').split(",");
            listArray.splice(index, 1);
            localStorage.setItem('list', listArray);
            return localStorage.getItem('list').split(",");
        }
    };
} ]);
