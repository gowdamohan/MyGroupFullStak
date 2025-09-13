<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-5">
            <div class="global">
                <span class="global-value">15.1</span>
                <div class="rating-icons">
                    <span class="one"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                    <span class="two"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                </div>
                 <span class="total-reviews">0</span>
            </div>
        </div>
        <div class="col-1">
            <hr style="background: #9e9e9e;height: 100%;margin-top: 0;" >
        </div>
        <div class="col-6" style="padding-left:0">
            <div class="chart">
                <div class="rate-box">
                    <span class="value">5</span>
                    <div class="progress-bar">
                        <span class="progress"></span>
                    </div>
                    <span class="count">10</span>
                </div>
                <div class="rate-box">
                    <span class="value">4</span>
                    <div class="progress-bar"><span class="progress"></span></div>
                    <span class="count">8</span>
                </div>
                <div class="rate-box">
                    <span class="value">3</span>
                    <div class="progress-bar"><span class="progress"></span></div>
                    <span class="count">7</span>
                </div>
                <div class="rate-box">
                    <span class="value">2</span>
                    <div class="progress-bar"><span class="progress"></span></div>
                    <span class="count">5</span>
                </div>
                <div class="rate-box">
                    <span class="value">1</span>
                    <div class="progress-bar"><span class="progress"></span></div>
                    <span class="count">3</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .chart {
  /* width: 500px; */
  display: flex;
  justify-content: space-between;
  flex-direction: column;
  height: 100%;
}

.chart .rate-box {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
  height: 15px;
  padding: 20px 0;
  padding: 0px;
}
.chart .rate-box > * {
/*  height: 100%;*/
  display: flex;
/*  align-items: center;*/
/*  font-weight: 500;*/
  color: #444;
}
.rate-box .value {
  display: flex;
  align-items: center;
}
.rate-box .value:hover {
  color: #66bb6a;
}
.chart .value {
/*  font-size: 30px;*/
  cursor: pointer;
}
.rate-box .progress-bar {
  border-width: 1px;
  position: relative;
  background-color: #cfd8dc91;

  height: 10px;
  border-radius: 100px;
  width: 350px;
}
.rate-box .progress-bar .progress {
  background-color: #66bb6a;
  height: 100%;
  border-radius: 100px;
  transition: 300ms ease-in-out;
}

.global {
  height: 100%;
  width: 150px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 8px;
}
.one .fas {
  color: #cfd8dc;
}

.two {
  background: linear-gradient(to right, #66bb6a 0%, transparent 0%);
  -webkit-background-clip: text !important;
  -webkit-text-fill-color: transparent;
  transition: 0.5s ease-in-out all;
}

.global > span {
/*  font-size: 90px;*/
  font-weight: 500;
}

.rating-icons {
/*  display: flex;*/
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  height: 10%;
}
.rating-icons span {
  position: absolute;
  display: flex;
/*  font-size: 30px;*/
  left: 50%;
  transform: translateX(-50%);
  margin-bottom: 5px;
}

.total-reviews {
/*  font-size: 25px !important;*/
}
</style>

<script type="text/javascript">
    let rateBox = Array.from(document.querySelectorAll(".rate-box"));
let globalValue = document.querySelector(".global-value");
let two = document.querySelector(".two");
let totalReviews = document.querySelector(".total-reviews");
let reviews = {
  5: 10,
  4: 4,
  3: 3,
  2: 2,
  1: 1,
};
updateValues();

function updateValues() {
  rateBox.forEach((box) => {
    let valueBox = rateBox[rateBox.indexOf(box)].querySelector(".value");
    let countBox = rateBox[rateBox.indexOf(box)].querySelector(".count");
    let progress = rateBox[rateBox.indexOf(box)].querySelector(".progress");
    console.log(typeof reviews[valueBox.innerHTML]);
    countBox.innerHTML = nFormat(reviews[valueBox.innerHTML]);

    let progressValue = Math.round(
      (reviews[valueBox.innerHTML] / getTotal(reviews)) * 100
    );
    progress.style.width = `${progressValue}%`;
  });
  totalReviews.innerHTML = getTotal(reviews);
  finalRating();
}
function getTotal(reviews) {
  return Object.values(reviews).reduce((a, b) => a + b);
}

document.querySelectorAll(".value").forEach((element) => {
  element.addEventListener("click", () => {
    let target = element.innerHTML;
    reviews[target] += 1;
    updateValues();
  });
});

function finalRating() {
  let final = Object.entries(reviews)
    .map((val) => val[0] * val[1])
    .reduce((a, b) => a + b);
    // console.log(typeof parseFloat(final / getTotal(reviews)).toFixed(1));
  let ratingValue = nFormat(parseFloat(final / getTotal(reviews)).toFixed(1));
  globalValue.innerHTML = ratingValue;
  two.style.background = `linear-gradient(to right, #66bb6a ${
    (ratingValue / 5) * 100
  }%, transparent 0%)`;
}

function nFormat(number) {
  if (number >= 1000 && number < 1000000) {
    return `${number.toString().slice(0, -3)}k`;
  } else if (number >= 1000000 && number < 1000000000) {
    return `${number.toString().slice(0, -6)}m`;
  } else if (number >= 1000000000) {
    return `${number.toString().slice(0, -9)}md`;
  } else if (number === "NaN") {
    return `0.0`;
  } else {
    return number;
  }
}

</script>