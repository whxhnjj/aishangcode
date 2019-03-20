//获取应用实例
Page({
  data: {
    userInfo: {},
    imgalist: ['http://t.nongjia365.com.cn/guanli/assets/img/name_img.jpg'],
  },
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return {
      title: '喜迎新年一起笑',
      path: 'theme/theme',
      success: function (res) {
        wx.showToast({
          title: '成功',
          icon: 'success',
          duration: 2000
        })
      },
      fail: function (res) {
        wx.showToast({
          title: '失败',
          icon: 'success',
          duration: 2000
        })
      }
    }
  },
  //评价图片预览
  previewImage: function (e) {
    var current = e.target.dataset.src;
    wx.previewImage({
      current: current, // 当前显示图片的http链接  
      urls: this.data.imgalist // 需要预览的图片http链接列表  
    })
  },
})
