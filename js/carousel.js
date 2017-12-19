angular.module('app', ['ui.bootstrap']);
function Carousel($scope){
  $scope.myInterval = 3000;
  $scope.slides = [
    {
      image: '../images/barberry1.jpg'
    },
    {
      image: '../images/barberry2.jpg'
    },
    {
      image: 'images/barberry3.jpg'
    },
    {
      image: 'images/barberry4.jpg'
    }
  ];
}