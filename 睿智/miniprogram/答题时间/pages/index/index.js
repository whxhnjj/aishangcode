const app = getApp();
var original;
var page = 1 

Page({
  //页面的初始数据
  data: {
    domain: app.globalData.domain,
    exams: [],
    isReachBottom: 1,
    footer: '',
  },

  //监听页面加载
  onLoad: function (e) {
    page = 1;
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

  // getexam: function(e){
  //   let that=this
  //   var exam_id = e.currentTarget.dataset.id 
  //   that.getUserData(function(){
  //     wx.navigateTo({
  //       url: '../exam/exam?exam_id='+exam_id,
  //     })

  //   },function(){
  //     wx.navigateTo({
  //       url: '../userinfo/userinfo?exam_id=' + exam_id,
  //     })
  //   })
  // },

  


  //获取数据
  getData: function (original) {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/exam/sel', //所有试卷接口地址
      method: 'POST',
      data: {
        page:page
      },
      success: function (res) {
        console.log(res)
        if (res.data.code > 0) {
          if (page == 1) {
            that.setData({
              exams: res.data.data,
              isReachBottom: 1,
            });
          }

          if (res.data.data.length == 0) {
            that.setData({ isReachBottom: 0, footer: '已到达我的底线'});
          }
          else {
            var n = original;
            for (var i = 0; i < res.data.data.length; i++) {
              n.push(res.data.data[i])
            }
            that.setData({
              exams: n,
            });
            page++;
          }
        }
        else {
          wx.showToast({ title: '试卷信息加载失败，刷新试试。' });
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

  onShareAppMessage: function () {

  }
})