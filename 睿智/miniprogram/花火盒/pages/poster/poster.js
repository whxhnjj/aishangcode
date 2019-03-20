const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    poster:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var actor_id = e.actor_id

    //二维码显示
    var that = this;
    wx.showLoading({title:"图片加载中"});
      //调用接口
      wx.request({
        url: app.globalData.domain + '/api/wechat/getposter', //仅为示例，并非真实的接口地址
        header: {
          'content-type': 'application/x-www-form-urlencoded',
        },
        method: 'POST',
        data: {
          actor_id: actor_id,
        },
        success: function (res) {
          console.log(res)
          if (res.data.code > 0) {
            that.setData({
              poster: app.globalData.domain+'/'+res.data.data,
            })
          } else {
            var msg = '数据加载失败'
            if (res.data.code == -9){
              msg = '审核尚未完成'
            }
            wx.showToast({
              title: msg,
              duration:2000
            });
          }
        },
        fail: function (res) {
          wx.showToast({ title: '数据加载出错' });
        },
        complete: function () {
          wx.hideLoading();
        }
      })
  },

  //系统保存图片
  save:function(){
    var that = this
    wx.getImageInfo({
      src: that.data.poster,
      success: function (res) {

        wx.saveImageToPhotosAlbum({
          filePath: res.path,
          success(res) {
            wx.showToast({
              title: '保存成功',
            })
          }
        })

      }
    })

  },



  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})