const app = getApp()
var theme_id
Page({
  data: {
    photos:[],
    countIndex:9,
    count: [1, 2, 3, 4, 5, 6, 7, 8, 9],
    sex:['男','女'],
    songs_list:['回娘家','忠诚','小胡燕','陕北人','绥德汉','黄米酒','天下名州','心向北京','可爱的人','平安榆林','平安榆阳','多彩榆林','我就是我','我是警察',
'往人前走','高原传奇','贱枣','榆林挺住','榆林八景','豪情绥德','无定河恋歌','我的赤牛洼','情系无定河','麟州坊之歌','八一宾馆之歌','人民警察之歌','青山下的想念',
'人生不容易','黄土情腰鼓梦','新时代的天堂','绥德汉家乡梦','今生我要守着你','米脂婆姨绥德汉','米脂的婆姨绥德的汉','陕北我如此爱你','咱们二人不分手',
'狼吃狗咬不后悔','骑驴婆姨赶驴汉','这辈子就和哥哥好','难活不过人想人','英雄的名字叫警察','绥德抚院马氏之歌','知心的话儿要拉遍','三哥哥是我的心上人',
'好不容易遇到一达达','硷畔上过来个四妹妹','有你的地方我就觉得美','老百姓永远把你记心头']

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

    if (!that.data.sex_value) {
      wx.showModal({
        title: '提示',
        content: '请选择性别',
        showCancel: false
      })
      return false
    }

    if (!that.data.songs_value) {
      wx.showModal({
        title: '提示',
        content: '请选择参赛曲目',
        showCancel: false
      })
      return false
    }

    if (!that.data.date_value) {
      wx.showModal({
        title: '提示',
        content: '请选择出生日期',
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
            content: '性别 ：' + that.data.sex_value + '|||参赛曲目 ：' + that.data.songs_value + '|||个人简历及获奖情况 ：' + content + '|||出生日期 ：' + that.data.date_value,
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
  },


  sexChange: function(e){
    let that = this;
    that.setData({
      sex_index: e.detail.value,
      sex_value: that.data.sex[e.detail.value]
    });
    console.log(that.data.sex[e.detail.value])
  },

  songsChange: function(e){
    let that = this;
    that.setData({
      songs_index: e.detail.value,
      songs_value: that.data.songs_list[e.detail.value]
    });
    console.log(that.data.songs_list[e.detail.value])
  },

  dateChange: function (e) {
    let that = this;
    that.setData({
      date: e.detail.value,
      date_value: e.detail.value
    });
    console.log(e.detail.value)
  },


})

