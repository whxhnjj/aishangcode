const app = getApp()
var theme_id
Page({
  data: {
    photos:[],
    countIndex:9,
    count: [1, 2, 3, 4, 5, 6, 7, 8, 9]
  },
  onLoad:function(e){
    if (!app.globalData.userInfo) {
      wx.redirectTo({
        url: '../login/login?url=' + encodeURIComponent('../signup/signup?theme_id=' + e.theme_id)
      });
      return;
    }
    
    app.getUserInfo()
    app.globalData.theme_id = e.theme_id
    theme_id = e.theme_id
    this.getData(theme_id)

  },
  getData: function (theme_id) {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/theme/getthemebase',
      method: 'POST',
      data: {
        theme_id:theme_id
      },
      success: function (res) {
        console.log(res.data)
        if (res.data.code > 0) {
          that.setData({
            theme: res.data.data,
          });
        }else {
          wx.showToast({ title: '加载失败' });
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
  //用户点击右上角分享
  onShareAppMessage: function () {

  },
  countChange: function (e) {
    this.setData({
      countIndex: e.detail.value
    })
  },
  //选择照片或拍照
  chooseImage: function () {
    var that = this
    wx.chooseImage({
      count: this.data.count[this.data.countIndex],   
      success: function (res) { 
        console.log(res.tempFilePaths.length)
        var photos = that.data.photos
        for (var i = 0; i < res.tempFilePaths.length; i++){
          photos.splice(photos.length, 0, res.tempFilePaths[i]);
          console.log(photos)
        }
        
        that.setData({
          photos: photos
        })
      
      }
      
    })
  },
  //预览图片
  previewImage: function (e) {
    var current = e.target.dataset.src
    wx.previewImage({
      current: current,
      urls: this.data.photos
    })
  },
  // 删除图片
  deleteImg: function (e) {
    var that = this
    console.log(e)
    wx.showModal({
      title: '提示',
      content: '确定删除图片吗？',
      success: function (res) {
        console.log(res)
        if (res.confirm){
          var index = e.currentTarget.dataset.index
          var photos = that.data.photos
          photos.splice(index, 1);
          that.setData({
            photos: photos
          });
        }

      }
    })
  },

  
  //提交
  formSubmit:function(e){
    var that = this
    that.submit(e)
    //用户授权设置
    // wx.getSetting({
    //   success: (res) => {
    //     if(! res.authSetting['scope.userInfo']){
    //       wx.openSetting({
    //         success: (res2) => {
    //           if (res2.authSetting['scope.userInfo']) {
    //             wx.getUserInfo({
    //               withCredentials: false,
    //               success: function (res3) {
    //                 app.globalData.userInfo = res3.userInfo
    //                 that.submit(e)
    //               }
    //             })                
    //           }else{
    //             //弹出授权提示
    //            wx.showModal({
    //              title: '提示',
    //              content: '授权后方可使用',
    //              showCancel: false
    //            })                
    //           }
    //         }
    //       })
    //     }else{
    //       that.submit(e)
    //     }
    //   }
    // })
  },

  //提交数据
  submit:function(e){
    var that = this
    var formId = e.detail.formId
    var user = app.globalData.userInfo
    console.log(user)


    var photos = this.data.photos
    var name = e.detail.value.name
    var phone = e.detail.value.phone
    var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;
    var content = e.detail.value.content

    if (photos.length == 0) {
      wx.showModal({
        title: '提示',
        content: '请先选择照片',
        showCancel: false
      })
      return false
    }
    if (name == '') {
      wx.showModal({
        title: '提示',
        content: '姓名不能为空',
        showCancel: false
      })
      return false
    }
    if (phone.length == '') {
      wx.showModal({
        title: '提示',
        content: '手机号不能为空',
        showCancel: false
      })
      return false;
    }
    if (phone.length > 11 || phone.length < 11 || !myreg.test(phone)) {
      wx.showModal({
        title: '提示',
        content: '手机号格式错误',
        showCancel: false
      })
      return false;
    }

    wx.showLoading({ title: '提交中...' });
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //获取发出的数据
        wx.request({
          url: app.globalData.domain + '/api/actor/add',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey,
            theme_id: theme_id,
            nickname: encodeURI(user.nickName),
            avatarurl: user.avatarUrl,
            gender: user.gender,
            name: name,
            phone: phone,
            content: content,
            form_id: formId
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              //上传
              app.uploadimg({
                id: res.data.data,
                url: app.globalData.domain + '/api/actor/upload',
                path: photos,
                success: function () { //自定义success
                  wx.hideLoading();
                  wx.showModal({
                    title: '报名成功',
                    content: '请等待审核',
                    showCancel: false,
                    success: function () {
                      wx.redirectTo({
                        url: '../detail/detail?actor_id=' + res.data.data + '&theme_id=' + theme_id,
                      })
                    }
                  })
                }
              })
            } else {
              wx.hideLoading();

              if (res.data.code == -2) {
                wx.showModal({
                  title: '报名失败',
                  content: '不可重复报名',
                  showCancel: false,
                  success: function () {
                    console.log(theme_id)
                    wx.switchTab({
                      url: '../theme/theme',
                    })
                  }
                })
              } else {
                wx.showToast({
                  title: res.data.code,
                  image: '/pages/images/cuowu.png',
                  duration: 10000
                })
              }
            }
          },
          fail: function (res) {
            wx.hideLoading();
            wx.showToast({ title: '提交失败' });
          }
        });

      } else {
        console.log('err:', err)
        that.setData({
          loading: false
        })
      }
    })
  }


})

