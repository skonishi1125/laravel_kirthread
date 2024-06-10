'use strict';

/**
 * リアルタイム時間の取得処理
 */
{
  countUp();

  function countUp() {
    const date = new Date();
    const day = date.getDay();
    const hour = String(date.getHours()).padStart(1, '0');
    const min = String(date.getMinutes()).padStart(2, '0');
    const sec = String(date.getSeconds()).padStart(2, '0');
    const dayArray = ['日','月','火','水','木','金','土'];

    const nowTime = document.getElementById('now-time');

    let timeoutId;
    timeoutId = setTimeout(() => {
      countUp();
      nowTime.textContent = (`${hour}:${min}:${sec} ${dayArray[day]}曜日`);
    }, 1000);
  }

}
