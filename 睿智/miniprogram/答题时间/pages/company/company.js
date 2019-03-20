// pages/company/company.js
const app = getApp()
var user_id;
var original;
var page = 1

Page({


  data: {
    domain: app.globalData.domain,
    exams: [],
    org: {},
    isReachBottom: 1,
    footer: ''
  },
  onLoad: function (e) {
    var that = this
    console.log(e)
    page = 1;
    user_id = e.user_id
    if (e.scene) { user_id = e.scene }
    this.getData([]);
  },

  //监听用户上拉触底
  onReachBottom: function () {
    if (this.data.isReachBottom) {
      original = this.data.exams;
      this.getData(original);
    }
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    page = 1;
    this.getData([]);
    wx.stopPullDownRefresh()
  },

  getData: function (original) {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        wx.request({
          url: app.globalData.domain + '/api/orgs/getorg', //所有试卷接口地址
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            user_id: user_id,
            skey: skey,
            page: page
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              if (page == 1) {
                that.setData({
                  exams: res.data.data.exam,
                  org: res.data.data.org,
                  isReachBottom: 1,
                });
              }

              if (res.data.data.exam.length == 0) {
                that.setData({ isReachBottom: 0, footer: '已到达我的底线' });
              }
              else {
                var n = original;
                for (var i = 0; i < res.data.data.exam.length; i++) {
                  n.push(res.data.data.exam[i])
                }
                that.setData({
                  exams: n,
                });
                page++;
              }

            }
            else {
              wx.showToast({ title: '企业信息加载失败' });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '数据加载失败' });
          },
          complete: function () {
            wx.hideLoading();
          }
        })
      }
    })
  },

  //分享
  onShareAppMessage: function () {

  }
})