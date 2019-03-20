const app = getApp()
var exam_id;
var times

Page({
  data: {
    domain: app.globalData.domain,
    disabled:false
  },
  //生命周期函数--监听页面加载
  onLoad: function (e) {
    var that = this
    console.log(e)
    exam_id = e.exam_id
    if (e.scene) { exam_id = e.scene }
    this.getData(); 
    //更新数据
    that.setData({
      exam_id: exam_id,
      remain_times: ""
    })
  },
  onShow: function(){
    let that = this;
    that.setData({
      disabled: false
    })

  },

  // 获取用户电话姓名
  getUserData: function (scb, ecb) {
    var that = this
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        wx.request({
          url: app.globalData.domain + '/api/member/getmemberinfo', //用户接口地址
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey
          },
          success: function (res) {
            console.log(res)
            if (res.data.code > 0 && res.data.data.mobile != '') {
              scb && scb(res)
            } else {
              ecb && ecb(res)
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





  getUserInfo: function(e){
    var that=this;
    console.log(e)
    that.setData({disabled: true})
    that.getData(function(res){
      console.log(1,res)
      if (res.exam.isvalid == -1) {
        wx.showModal({
          title: '提示',
          content: '答题活动还未开始',
          showCancel: false
        })
        that.setData({ disabled: false })
        return false
      }
      if (res.exam.isvalid == -2) {
        wx.showModal({
          title: '提示',
          content: '答题活动已经结束',
          showCancel: false
        })
        that.setData({ disabled: false })
        return false
      }
      if (app.globalData.own_times < times) {
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
          success: function (res) {
            console.log(res.data)
          },
          fail: function (res) {
          }
        })
        //是否需要填写用户信息
        if(res.exam.is_userinfo == 1){
          that.getUserData(function () {
            wx.navigateTo({
              url: "../subject/subject?exam_id=" + exam_id + "&times=" + times,
           })
            return
          }, function () {
            wx.navigateTo({
              url: '../userinfo/userinfo?exam_id=' + exam_id + "&times=" + times,
            })
            return
          })
        }else{
          wx.navigateTo({
            url: "../subject/subject?exam_id=" + exam_id + "&times=" + times,
          })
        }

      } else {
        wx.showModal({
          title: '提示',
          content: '答题次数已超过个人限制',
          showCancel: false
        }) 
        that.setData({ disabled: false })
        return false
      }
      // console.log(own_times)
      console.log(times)
    })
  },



  //获取数据
  getData: function (cb) {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    app.getUserOpenId(function (err, skey) {
    if (!err) {
    wx.request({
      url: app.globalData.domain + '/api/exam/get', //所有试卷接口地址
      method: 'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
        'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
      },
      data: {
        exam_id: exam_id,
        skey: skey
      },
      success: function (res) {
        console.log(res.data)
        if (res.data.code > 0) {
         
          var exam_times = res.data.data.exam.times
          if (exam_times>0){
            times = exam_times
            var remain_times = times - res.data.data.own.times
            if (remain_times < 0) { remain_times = 0 }
            app.globalData.own_times = res.data.data.own.times
      
          }
          if (exam_times<0){
            
            times = Math.abs(exam_times)
            
            var remain_times = times - res.data.data.own.today_times
            if (remain_times < 0) { remain_times = 0 }
            app.globalData.own_times = res.data.data.own.today_times

          }
          if (exam_times == 0){ 
            times = 1
            var remain_times = 0
          }
          that.setData({
            exam: res.data.data.exam,
            remain_times: remain_times,
            times:times,
            own: res.data.data.own
          })

          cb && cb(res.data.data)
          
        }
        else {
          wx.showToast({ title: '暂无试卷' });
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

  //用户点击右上角分享
  onShareAppMessage: function () {
    return {
      title: this.data.exam.title,
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  }

})