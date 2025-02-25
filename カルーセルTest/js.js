const next = document.getElementById('next');
const prev = document.getElementById('prev');
const ul = document.querySelector('ul');
const slides = ul.children;
const navButtons = document.querySelectorAll('nav button');
let currentindex = 0;

function updatebuttons () {
  next.classList.remove('hidden');
  prev.classList.remove('hidden');

  if (currentindex === 0) {
    prev.classList.add('hidden');
  }
  if (currentindex === slides.length - 1) {
    next.classList.add('hidden');
  }
}

function updateNavButtons() {
  navButtons.forEach((button, index) => {
    if (index === currentindex) {
      button.style.backgroundColor = '#333'; // アクティブなスライドのボタンの色
    } else {
      button.style.backgroundColor = '#ddd'; // 非アクティブなスライドのボタンの色
    }
  });
}

function moveslides () {
  const slideswidth = slides[0].getBoundingClientRect().width;
  ul.style.transform = `translateX(${-1 * slideswidth * currentindex}px)`;
  updatebuttons();
  updateNavButtons();
}

next.addEventListener('click', () => {
  currentindex ++;
  moveslides();
});

prev.addEventListener('click', () => {
  currentindex --;
  moveslides();
});

// 初期状態のボタンの色を設定
updatebuttons();
updateNavButtons();