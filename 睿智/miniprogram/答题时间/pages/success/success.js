const app = getApp()
var exam_id;
var state;
Page({
  data: {
    state:""

  },
  onLoad: function (e) {
    var that = this
    console.log(e)
    exam_id = e.exam_id
    state=e.state
    if (e.scene) { exam_id = e.scene }
    that.setData({
      state:state
    })
    console.log(state)

  },
  submit: function () {
    wx.redirectTo({
      url: '../exam/exam?exam_id='+ exam_id
    })
  }
})