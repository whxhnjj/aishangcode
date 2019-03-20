const app = getApp()
var original
var page = 1
Page({

  //页面的初始数据
  data: {
    domain: app.globalData.domain,
    footer: '',
    isReachBottom: 1
  },
  
  //生命周期函数--监听页面加载
  onLoad: function (options) {
    if (!app.globalData.userInfo) {
      wx.redirectTo({
        url: '../login/login?url=' + encodeURIComponent('../list/list')
      });
      return;
    }
    
    app.getUserInfo()
    page = 1;
    this.getData([]);
  },

  //监听用户上拉触底
  onReachBottom: function () {
    if (this.data.isReachBottom) {
      original = this.data.actors;
      this.getData(original);
    }
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    page = 1;
    this.getData([]);
    wx.stopPullDownRefresh()
  },


  shomode:function(){
    wx.showModal({
      title: '温馨提醒',
      content: '票数相同的情况下，报名较早者排名优先，特此说明。',
      showCancel: false,
      confirmText: '我知道了',
      confirmColor:'#11D4C6'
    })
  },

  //用户点击右上角分享
  onShareAppMessage: function () {
    var that = this
    return {
      title: '榜单',
      path: '/pages/route/route?url=list&theme_id=' + app.globalData.theme_id,
    }
  },

  //获取数据
  getData: function (original){
    var that = this
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //获取发出的数据
        wx.showLoading({ title: '加载中' });
        wx.request({
          url: app.globalData.domain +'/api/actor/getBillboard',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            theme_id: app.globalData.theme_id,
            skey: skey,
            page: page

          },
          success: function (res) {
            console.log(res)
            if (res.data.code > 0) {
              if (page == 1) {
                that.setData({
                  actor: res.data.data.actor,
                  isReachBottom: 1, 
                });
              }

              if (res.data.data.actors.length == 0) {
                that.setData({ isReachBottom:0, footer: '已到达我的底线' });
              }
              else {
                var n = original;
                for (var i = 0; i < res.data.data.actors.length; i++) {
                  n.push(res.data.data.actors[i])
                }
                that.setData({ 
                  actors: n ,
                  footer:'上拉加载更多'
                });
                page++;
              }
            }
            else {
              wx.showToast({ title: '数据加载失败，刷新试试。' });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '数据加载失败' });
          },
          complete: function () {
            wx.hideLoading();
          }
        });

      } else {
        console.log('err:', err)
        that.setData({
          loading: false
        })
      }
    })  
  },
})