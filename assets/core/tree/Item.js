var Item = (function () {
  var _this = this;
  var _home;
  var _url;

  return {
    init: function () {
      _this = this;

      return this;
    },

    render: function (items, url, home) {
      _home = home;
      _url = url;

      return _this.getItems(items);
    },

    getItems: function (items) {
      var temp = '';

      if (items && items.length != 0) {
        temp += '<ul class="item-container">';
      }

      for (var prop in items) {
        var item = items[prop];
        var move = (item.items && item.items.length > 0) ? 'move' : '';
        var url = item.url;

        if (item.type !== 'xpressengine@directLink') {
          if (item.id == _home) {
            url = '/';
          } else {
            url = '/' + url;
          }

          url = Utils.getUri(xeBaseURL + url);

        } else {
          url = url;
        }

        temp += "<li class='item " + move + "'>";
        temp +=   "<div class='item-content' data-item='" + JSON.stringify(item) + "'>";
        temp +=     "<button class='btn handler'><i class='xi-drag-vertical'></i></button>";
        temp +=     "<div class='item-info'>";
        temp +=       "<i class='xi-paper'></i>";
        temp +=       "<dl>";
        temp +=         "<dt class='sr-only'>" + XE.Lang.trans(item.title) + "</dt>";
        temp +=         "<dd class='ellipsis'><a href='" + _url + "/" + item.menuId + "/items/" + item.id + "'>" + XE.Lang.trans(item.title) + "</a></dd>";
        temp +=         "<dt class='sr-only'>" + url + "</dt>";
        temp +=         "<dd class='text-blue ellipsis'><a href='" + url + "'>" + url + "</a><em>[" + item.type + "]</em></dd>";
        temp +=       "</dl>";
        temp +=     "</div>";
        temp +=   "</div>";

        if (item.items && item.items instanceof Object) {
          temp += _this.getItems(item.items);
        }

        temp += '</liv>';

      }

      if (items && items.length != 0) {
        temp += '</ul>';
      }

      return temp;
      //}
    },
  }.init();
})();
