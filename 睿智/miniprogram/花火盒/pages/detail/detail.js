const app = getApp()
var theme_id
var actor_id
var photoList = []
var original
var page = 1
Page({

  //页面的初始数据
  data: {
    domain: app.globalData.domain,
    rownum:1,
    more:'更多',
    isMore:1,
    nullHouse: true,
    noticehide: true, 
  },
  //生命周期函数--监听页面加载
  onLoad: function (e) {
    console.log(e)
    
    page = 1;
    actor_id = e.actor_id
    if (e.scene) { actor_id = e.scene }
    this.getData([]);
    var that = this
    //更新数据
    that.setData({
      actor_id: actor_id,
      userInfo: app.globalData.userInfo,
    })
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
  
  //弹出操作框
  depict: function () {
    this.setData({
      nullHouse: false, //弹窗显示
    })
  },
  //取消操作框
  cancel: function () {
    this.setData({
      nullHouse: true, //弹窗关闭
    })
  },
  //收缩文字
  onChangeShowState: function () {
    var that = this;
    that.setData({
      showView: (!that.data.showView)
    })
  },
  //长按识别图片
  longpress:function(e){
    if(this.data.actor.is_self == 0){return}
    var path = e.target.dataset.path
    wx.showActionSheet({
      itemList: ['设为封面'],
      success: function (res){
        switch (res.tapIndex){
          case 0:
            wx.request({
              url: app.globalData.domain + '/api/actor/setthumb',
              method: 'POST',
              data: {
                actor_id: actor_id,
                path: path
              },
              success: function (res) {
                if(res.data.code == 1){
                  wx.showToast({
                    title: '设置成功',
                    icon: 'success',
                    mask:'true',
                  })
                }else{
                  wx.showToast({
                    title: '设置失败',
                    image: '/pages/images/cuowu.png',
                    mask: 'true',
                  })

                }
              }
            })


        }
      },
      fail: function (res) {
        console.log(res.errMsg)
      }
    })
  },

  toGift:function(){
    var that = this
    var deadline = that.data.theme.deadline
    var totalSecond = deadline - Date.parse(new Date()) / 1000;
    that.setData({
      style_img: 'transform:scale(1.2);'
    })
    setTimeout(function () {
      that.setData({
        style_img: 'transform:scale(1);'
      })
    }, 1000)
    if (totalSecond <= 0) {
      wx.showModal({
        title: '支持失败',
        content: '活动已结束',
        showCancel: false,
      })
    }else{
      wx.navigateTo({
        url: '../gift/gift?theme_id=' + theme_id+'&actor_id=' + actor_id
      })
    }
  },
  toSignup: function () {
    var that = this
    var deadline = that.data.theme.deadline
    var totalSecond = deadline - Date.parse(new Date()) / 1000;
    if (totalSecond <= 0 || that.data.theme.status == 1) {
      wx.showModal({
        title: '报名失败',
        content: '活动不可报名或已结束',
        showCancel: false,
      })

    } else {
      wx.navigateTo({
        url: '../signup/signup?theme_id=' + theme_id
      })
    }
  },
  //获取数据
  getMore:function(){
    if (this.data.isMore) {
      original = this.data.gifts;
      this.getData(original);
    }
  },
  getData: function (original){
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    app.getUserOpenId(function (err, skey) {
      if (!err) {
    
        //调用接口
        wx.request({
          url: app.globalData.domain +'/api/actor/getactor', //仅为示例，并非真实的接口地址
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          method: 'POST',
          data: {
            skey:skey,
            actor_id: actor_id,
            page: page,
            pagesize:10

          },
          success: function (res) {
            console.log(res)
            if (res.data.code > 0) {

              if (res.data.data.actor) {
                
                wx.setNavigationBarTitle({
                  title: res.data.data.theme.title
                })

                var actor = res.data.data.actor
                app.globalData.theme_id = actor.theme_id
                theme_id = actor.theme_id


                if (!actor.avatarurl){
                  actor.avatarurl = app.globalData.domain + '/' + actor.thumb;
                }



                var json = "{" + res.data.data.actor.photo + "}";
                var photos = JSON.parse(json)
                
                var rownum = 1;
                var len = Object.keys(photos).length
                photoList = []
                for (var i = 0; i < len; i++) {
                  photoList[i] = app.globalData.domain + '/' + photos[i];
                }
                switch (len) {
                  case 1:
                    rownum = 1
                    break
                  case 2:
                  case 4:
                    rownum = 2
                    break
                  default:
                    rownum = 3
                }
                that.setData({
                  theme_id: actor.theme_id,
                  theme: res.data.data.theme,
                  actor: actor,
                  votes: res.data.data.votes,
                  notices: res.data.data.notices,
                  photos: photos,
                  rownum: rownum,
                  isMore: 1
                });
              }
              if (res.data.data.gifts.length == 0) {
                that.setData({ isMore: 0, more: '没有更多了' });
              }
              else {
                var n = original;
                for (var i = 0; i < res.data.data.gifts.length; i++) {
                  res.data.data.gifts[i].nickname = decodeURI(res.data.data.gifts[i].nickname)
                  n.push(res.data.data.gifts[i])
                }
                that.setData({ gifts: n });
                page++;
              }
            } else {
              wx.showToast({
                title: '数据加载失败'
              });
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
  previewImage: function (e) {
    console.log(photoList)
    var current = e.target.dataset.src
    wx.previewImage({
      current: current,
      urls: photoList
    })
  },

  //用户点击右上角分享
  onShareAppMessage: function () {
    var that = this
    return {
      title: that.data.actor.name + '喊你来支持！',
       success: function(res) {
        wx.showToast({
          title: '转发成功',
          icon: 'success',
        })
        },
        fail: function (res) {
          wx.showToast({
            title: '转发失败',
            image: '/pages/images/cuowu.png',
          })       
        }
    }
  }
})