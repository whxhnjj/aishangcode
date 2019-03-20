
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

Page({

  onLoad: function () {
    let that = this;
    that.getInfo();
  },


  //获取说明
  getInfo: function () {
    let that = this;
    core.request({
      url: 'api/complete/instructions',
      method: 'GET',
      success: function (res) {
        that.setData({
          instructions: res.data.instructions
        });
      },
      fail: function (res) {
      }
    });
  }

})