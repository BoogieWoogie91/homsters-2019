$(document).ready(function(){
        $('.partnersSlide').slick({
          infinite: true,
          speed: 600,
          slidesToShow: 1,
          centerMode: true,
          variableWidth: true,
          accessibility: true,
          autoplay: true,
          arrows: true,
          initialSlide: 1,
          responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
    });
});

$(document).ready(function(){
        $('.photoSlide').slick({
          infinite: true,
          speed: 600,
          slidesToShow: 1,
          centerMode: true,
          variableWidth: true,
          accessibility: true,
          autoplay: false,
          arrows: true,
          initialSlide: 1,
          responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
    });
});

var closeModal = document.querySelectorAll('.closeModal');
var exampleModalCenter = document.getElementById('exampleModalCenter');
var modalFader = document.getElementsByClassName('modal-backdrop');



for (var i = 0; i < closeModal.length; i++) {
  closeModal[i].onmouseup = function() {
    exampleModalCenter.style.display = 'none';
    document.body.removeChild(modalFader[0])
    console.log('somethingHappened');
    }
};
