const app = getApp();
var exam_id;
var original;
var page = 1

Page({
  data: {
    domain: app.globalData.domain,
    records: [],
    isReachBottom: 1,
    footer: '',
  },

  //生命周期函数--监听页面加载
  onLoad: function (e) {
    exam_id = e.exam_id
    if (e.scene) { exam_id = e.scene }
    var that = this
    //更新数据
    that.setData({
      exam_id: exam_id,
      user: app.globalData.user,
    })

    page = 1;
    that.getData([]);

  },


  //监听用户上拉触底
  onReachBottom: function () {
    if (this.data.isReachBottom) {
      original = this.data.records;
      this.getData(original);
    }
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    page = 1;
    this.getData([]);
    wx.stopPullDownRefresh()
  },

  //获取数据
  getData: function (original) {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //调用接口
        wx.request({
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          url: app.globalData.domain + '/api/record/sel', //排行榜接口地址
          method: 'POST',
          data: {
            exam_id: exam_id,
            skey: skey,
            page: page
          },
          success: function (res) {
            if (res.data.code > 0) {
              for (var i = 0; i < res.data.data.all.length; i++) {
                res.data.data.all[i].nickname = decodeURI(res.data.data.all[i].nickname)
                res.data.data.all[i].small_score = String(res.data.data.all[i].score).slice(-2);
                res.data.data.all[i].score = parseInt(res.data.data.all[i].score / 100)
              }
              if (page == 1) {
                res.data.data.own.nickname = decodeURI(res.data.data.own.nickname)
                res.data.data.own.small_score = String(res.data.data.own.score).slice(-2);
                res.data.data.own.score = parseInt(res.data.data.own.score / 100)
                that.setData({
                  records_own: res.data.data.own,
                  records: res.data.data.all,
                  isReachBottom: 1
                });
              }
              if (res.data.data.all.length == 0 || page >= 11) {
                that.setData({ isReachBottom: 0, footer: '已到达我的底线' });
              } else {
                var n = original;
                for (var i = 0; i < res.data.data.all.length; i++) {
                  n.push(res.data.data.all[i])
                }
                that.setData({
                  records: n,
                });
                page++;
              }

            }
            else {
              wx.showToast({ title: '暂无排行信息' });
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

  //用户分享
  onShareAppMessage: function () {
    return {
      title: "排行榜"
    }
  }
})