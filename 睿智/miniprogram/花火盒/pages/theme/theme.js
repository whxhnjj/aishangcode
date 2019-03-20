const app = getApp()
var deadline
var original
var page = 1
var theme_id
var isFirst;
Page({
  data: {
    domain: app.globalData.domain,
    countDownDay:0,
    countDownHour: 0,
    countDownMinute:0,
    countDownSecond:0,
    footer:'上拉加载更多',
    isReachBottom:1,
    totalSecond:1,
    noticehide: true, 
    },
  onLoad: function (e) {
    if (!app.globalData.userInfo) {
      wx.redirectTo({
        url: '../login/login?url=' + encodeURIComponent('../theme/theme')
      });
      return;
    }
    
    app.getUserInfo()
    isFirst = 1
    theme_id = app.globalData.theme_id
    page = 1
    this.getData([])

  },
  //弹出通知
  noticeOpen: function (e) {
    var that = this
    var index = e.currentTarget.dataset.index
    console.log(that.data.notices[index])
    this.setData({
      notice: that.data.notices[index],
      noticehide: false, //弹窗显示
    })
  },
  //关闭通知
  noticeClose: function () {
    var that = this;
    this.setData({
      noticehide: true, //关闭
    })
  },

  //监听用户上拉触底
  onReachBottom: function () {
    if (this.data.isReachBottom){
      original = this.data.actors
      this.getData(original)
    }
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    page = 1
    this.getData([])
    wx.stopPullDownRefresh()
  },

  //搜索
  formSubmit:function(e){
    var name = e.detail.value.name;
    if(name == ""){
      wx.showToast({
        title: '不能为空',
        image:'../images/cuowu.png'
      })
    }else{
    wx.navigateTo({
      url: '../search/search?key=' + name+'&theme_id='+theme_id,
    })
    }
  },

  //用户点击右上角分享
  onShareAppMessage: function () {
    var that = this
    return {
      title: app.globalData.userInfo.nickName + '邀请你参加[' + that.data.theme.title +']活动',
      path: '/pages/route/route?url=theme&theme_id=' + theme_id
    }
  },

  //获取数据
  getData:function(original){
    var that = this;
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain +'/api/theme/gettheme', //仅为示例，并非真实的接口地址
      method: 'POST',
      data: {
        theme_id: theme_id,
        page: page,
        pagesize: 10
      },
      success: function (res) {
        console.log(res)
        if (res.data.code > 0) {
          if (res.data.data.theme){
            deadline = res.data.data.theme.deadline
            app.globalData.deadline = deadline
            wx.setNavigationBarTitle({
              title: res.data.data.theme.title
            })
            if(isFirst == 1)
            {that.countdown(deadline);}
            isFirst = 0;
            that.setData({
              theme: res.data.data.theme,
              notices: res.data.data.notices,
              dateline: res.data.data.dateline,
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
            that.setData({ actors: n });
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
    })

  },


  //处理时间倒计时
  countdown:function (deadline) {
    var that = this
    var totalSecond = deadline - Date.parse(new Date()) / 1000;
    that.setData({ totalSecond: totalSecond });

    var interval = setInterval(function () {
      // 秒数  
      var second = totalSecond;

      // 天数位  
      var day = Math.floor(second / 3600 / 24);
      var dayStr = day.toString();
      if (dayStr.length == 1) dayStr = '0' + dayStr;

      // 小时位  
      var hr = Math.floor((second - day * 3600 * 24) / 3600);
      var hrStr = hr.toString();
      if (hrStr.length == 1) hrStr = '0' + hrStr;

      // 分钟位  
      var min = Math.floor((second - day * 3600 * 24 - hr * 3600) / 60);
      var minStr = min.toString();
      if (minStr.length == 1) minStr = '0' + minStr;

      // 秒位  
      var sec = second - day * 3600 * 24 - hr * 3600 - min * 60;
      var secStr = sec.toString();
      if (secStr.length == 1) secStr = '0' + secStr;

      that.setData({
        countDownDay: dayStr,
        countDownHour: hrStr,
        countDownMinute: minStr,
        countDownSecond: secStr,
      });
      totalSecond--;
      if (totalSecond < 0) {
        clearInterval(interval);
      }
    }.bind(that), 1000);
  }
})