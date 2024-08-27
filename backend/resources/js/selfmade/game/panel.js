document.addEventListener( 'DOMContentLoaded' , function( e ) {

  console.log('パネル');
  var panels = [];

  var table = document.getElementById("table");
  // console.log(table);

  for (var i = 0; i < 5; i++) {
    var tr = document.createElement("tr");

    for (var j = 0; j < 5; j++) {
      var td = document.createElement("td");
      var index = i * 5 + j;
      td.index = index;
      if (index % 2 == 0) {
        td.classList.add("red");
      } else {
        td.classList.add("green");
      }
      td.classList.add("panel");
      td.onclick = click;
      tr.appendChild(td);
      panels.push(td);
    }

    table.appendChild(tr);

  }


  function click(e) {
  var i = e.srcElement.index;
  changeColor(i);

  }

  function changeColor(i) {

  // 中 上 下 左 右
  var array = [panels[i], panels[i + 5], panels[i - 5], panels[i - 1], panels[i + 1]];

  // クリックした要素が左端の要素だった場合
  if (array[0].index % 5 == 0) {
    array[3] = undefined;
  }

  // クリックした要素が右端の要素だった場合
  if (array[0].index % 5 == 4) {
    // 右をundefinedにする
    array[4] = undefined;
  }

  array.forEach(function (e) {
    if (e == undefined)  return;

    var i_color = e.className;
    if (i_color == "red panel") {
      e.className = "green panel";
    } else {
      e.className = "red panel";
    }

  });
  }

})