// pages/collar/collar.js
//获取应用实例
var app = getApp()
Page({
  data: {
  },
  onLoad: function (options) {
    var that = this

    app.getUserOpenId(function (err, openid) {
      if (!err) {

        //获取发出的数据
        wx.showLoading({ title: '加载中' });
        wx.request({
          url: 'https://womendehunli.com/api/theme/get',
          method: 'POST',
          data: {
            openid: openid,
            id: options.id
          },

          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              that.setData({
                theme: res.data.data,
              });

              wx.hideLoading();
            } else {
              wx.showToast({
                title: '请重刷',
                image: '../images/cuowu.png'
              });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '数据加载失败' });
          }
        });

      } else {
        console.log('err:', err)
        self.setData({
          loading: false
        })
      }
    })

    


  },
  /*设置图片是否对其他人可见 */
  actionSheetTap: function () {
    wx.showActionSheet({
      itemList: ['所有人可见', '发起者可见', '我是汉子都不可见'],
      success: function (e) {
        console.log(e.tapIndex)
      }
    })
  },
  //评价图片预览
  previewImage: function (e) {
    var current = e.target.dataset.src;
    wx.previewImage({
      current: current, // 当前显示图片的http链接  
      urls: this.data.imgalist // 需要预览的图片http链接列表  
    })
  },
   //上传图片
  chooseImage: function (e) {
    wx.chooseImage({
      count: 9, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths
      }
    })
  },
  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function (res) {

  }
})
