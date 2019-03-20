var util = require('../../utils/util.js');
// date.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    date: "2017-11"
  },
  //  点击日期组件确定事件  
  bindDateChange: function (e) {
    this.setData({
      date: e.detail.value
    })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})