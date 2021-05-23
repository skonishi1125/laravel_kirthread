'use strict';
// リアルタイムで時間を更新させよう
{
    console.log('読み込みテスト');
    //
    // // jsはDate使用時、それぞれの単位を別々に取得していく
    // const date = new Date();
    // console.log(date);
    // const time = date.getFullYear();
    //
    // const day = date.getDay();
    // console.log(day);
    //
    // const h = String(date.getHours()).padStart(2, '0');
    // const m = String(date.getMinutes()).padStart(2, '0');
    // const s = String(date.getSeconds()).padStart(2, '0');
    //
    // const dayArray = ['日','月','火','水','木','金','土'];
    // console.log(dayArray);
    //
    // console.log(`${h}:${m}:${s} ${dayArray[day]}`);
    // const nowTime = document.getElementById('now-time');
    // nowTime.textContent = (`${h}:${m}:${s} ${dayArray[day]}曜日`);

    let startTime;
    let timeoutId;
    function countUp() {
        const date = new Date();
        const day = date.getDay();
        const hour = String(date.getHours()).padStart(1, '0');
        const min = String(date.getMinutes()).padStart(2, '0');
        const sec = String(date.getSeconds()).padStart(2, '0');
        const dayArray = ['日','月','火','水','木','金','土'];

        const nowTime = document.getElementById('now-time');

        // console.log(`${hour}:${min}:${sec} ${dayArray[day]}曜日`);
        timeoutId = setTimeout(() => {
            countUp();
            nowTime.textContent = (`${hour}:${min}:${sec} ${dayArray[day]}曜日`);
        }, 1000);
    }
    countUp();
    // console.log('あああ');

}
