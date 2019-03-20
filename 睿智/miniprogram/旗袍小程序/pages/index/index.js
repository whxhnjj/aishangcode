const app = getApp()
var exam_id = 15;
var times =3;

Page({

  data: {
    domain: app.globalData.domain,
    disabled: false
  },

  onLoad: function() {},

  onShow: function() {
    let that = this;
    that.setData({ disabled: false })
  },
  
  onUnload: function(){
    that.setData({ disabled: false })
  },

  getUserInfo: function(e) {
    var that = this;
    that.setData({ disabled: true })

    app.getUserOpenId(function(err, skey) {
      if (!err) {

        if (e.detail.userInfo) {
          app.globalData.user = e.detail.userInfo
        } else {
          app.globalData.user = {
            nickName: "无名氏",
            avatarUrl: "http://dati.webimage.cn/static/xcx/avatar.jpg"
          }
        }

        wx.request({
          url: app.globalData.domain + '/api/member/updatememberbase',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: app.globalData.skey,
            nick_name: encodeURI(app.globalData.user.nickName),
            avatar_url: app.globalData.user.avatarUrl
          },
          success: function(res) {
            wx.navigateTo({
              url: "../answer/answer?exam_id=" + exam_id + "&times=" + times,
            })
          },
          fail: function(res) {
            that.setData({ disabled: false })
          }
        })
      }

    })

  },


  //用户点击右上角分享
  onShareAppMessage: function() {
    return {
      title: this.data.exam.title
    }
  }

})