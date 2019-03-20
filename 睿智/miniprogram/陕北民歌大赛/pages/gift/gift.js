const app = getApp()
var theme_id
var actor_id

Page({
  data: {
    domain: app.globalData.domain,
    catalogSelect: 1, //判断是否选中
    price: 0,
    selected: 0,
    submitText: '支持一下',
    subscribe: 0, // 当前会员对某公众号的关注状态:0为未关注,1为已关注
    urlFrom: '', // Url来源
    subscribeCofig: 0, // 关注开关
  },

  //生命周期函数--监听页面加载
  onLoad: function(e) {

    if (e.from && e.from == 'mp') {
      let urlParams = this.getUrlParamsFromStorage() ? JSON.parse(this.getUrlParamsFromStorage()) : {};
      console.log('giftPageUrlParamsFromStorage:', urlParams);
      if (JSON.stringify(urlParams) != '{}') {
        app.globalData.theme_id = urlParams.theme_id
        theme_id = urlParams.theme_id
        actor_id = urlParams.actor_id
        this.setData({
          urlFrom: e.from
        })
      } else {
        wx.redirectTo({
          url: '../index/index'
        });
        return;
      }
    } else {
      app.globalData.theme_id = e.theme_id
      theme_id = e.theme_id
      actor_id = e.actor_id
    }

    if (!app.globalData.userInfo) {
      wx.redirectTo({
        url: '../login/login?url=' + encodeURIComponent('../gift/gift?theme_id=' + theme_id + '&actor_id=' + actor_id)
      });
      return;
    }

    var that = this;
    that.setData({
      theme_id: theme_id,
      actor_id: actor_id
    })
    this.getData();
    this.getSubscribeConfig();
    this.getMemberInfo();
  },

  // 获取当前会员信息
  getMemberInfo: function() {
    var user = app.globalData.userInfo;
    var that = this
    app.getUserOpenId(function(err, skey) {
      if (!err && skey) {
        wx.showLoading({
          title: '加载中'
        });
        wx.request({
          url: app.globalData.domain + '/api/vote/getMemberInfo',
          method: 'get',
          header: {
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey
          },
          success: function(res) {
            wx.hideLoading();
            console.log('memberInfo:', res.data);
            that.setData({
              'subscribe': res.data.subscribe
            });
            console.log('subscribe_onGet:', that.data.subscribe);
            let subscribe = that.data.subscribe,
              theme_id = that.data.theme_id,
              actor_id = that.data.actor_id,
              urlFrom = that.data.urlFrom;
            if (subscribe != 1) {
              // 未关注时将url参数存储到本地缓存
              that.saveUrlParamsToStorage(JSON.stringify({
                "theme_id": theme_id,
                "actor_id": actor_id
              }));
              if (urlFrom == 'mp') {
                // 来源为微信公众号菜单时更新关注状态
                that.updateMemberSubscribeState();
              }
            }
          },
          fail: function(res) {
            wx.hideLoading();
            wx.showToast({
              title: '请求失败',
              image: '../images/cuowu.png'
            });
          }
        });
      } else {
        that.setData({
          loading: false
        })
      }
    })
  },

  // 更新当前会员对某公众号的关注状态
  updateMemberSubscribeState: function() {
    var user = app.globalData.userInfo;
    var that = this
    app.getUserOpenId(function(err, skey) {
      if (!err && skey) {
        wx.showLoading({
          title: '加载中'
        });
        wx.request({
          url: app.globalData.domain + '/api/vote/updateMemberSubscribeState',
          method: 'get',
          header: {
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey
          },
          success: function(res) {
            wx.hideLoading();
            console.log('subscribe_updateRes:', res.data);
            that.setData({
              'subscribe': 1
            });
            console.log('subscribe_onUpdate:', that.data.subscribe);
          },
          fail: function(res) {
            wx.hideLoading();
            wx.showToast({
              title: '请求失败',
              image: '../images/cuowu.png'
            });
          }
        });
      } else {
        that.setData({
          loading: false
        })
      }
    })
  },

  // 将本页面url相关参数存储到本地
  saveUrlParamsToStorage: function($urlParams) {
    wx.setStorageSync('giftPageUrlParams', $urlParams);
  },

  // 从本地缓存中读取本页面url相关参数
  getUrlParamsFromStorage: function() {
    return wx.getStorageSync('giftPageUrlParams');
  },

  // 获取关注开关配置
  getSubscribeConfig: function() {
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: app.globalData.domain + '/api/vote/getSubscribeConfig',
      success: function(res) {
        wx.hideLoading()
        console.log('subscribeCofig:', res.data);
        that.setData({
          'subscribeCofig': res.data.subscribeCofig
        })
      },
      fail: function(res) {
        wx.hideLoading()
        wx.showToast({
          title: '请求失败'
        });
      }
    })
  },

  getData: function() {
    var that = this
    console.log('actor_id',actor_id)
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/vote/get',
      method: 'POST',
      data: {
        actor_id: actor_id,
        page: 1
      },
      success: function(res) {
        console.log(res)
        if (res.data.code > 0) {
          wx.hideLoading()
          var default_gift = res.data.data.gifts[0];
          that.setData({
            actor: res.data.data.actor,
            gifts: res.data.data.gifts,
            gift_id: default_gift.id,
            gift_name: default_gift.name,
            price: default_gift.price,
            vote: default_gift.vote,
          });
          if (that.data.price > 0) {
            that.setData({
              'submitText': '微信支付'
            })
          }
        } else {
          wx.hideLoading()
          wx.showToast({
            title: '数据加载失败。'
          });
        }
      },
      fail: function(res) {
        wx.hideLoading()
        wx.showToast({
          title: '数据加载失败'
        });
      }
    })
  },



  //提交
  wxsubmit: function(e) {
    var that = this
    that.submit(e)
    //用户授权设置
    // wx.getSetting({
    //   success: (res) => {
    //     if (!res.authSetting['scope.userInfo']) {
    //       wx.openSetting({
    //         success: (res2) => {
    //           if (res2.authSetting['scope.userInfo']) {
    //             wx.getUserInfo({
    //               withCredentials: false,
    //               success: function(res3) {
    //                 app.globalData.userInfo = res3.userInfo
    //                 that.submit(e)
    //               }
    //             })
    //           } else {
    //             //弹出授权提示
    //             wx.showModal({
    //               title: '提示',
    //               content: '授权后方可使用',
    //               showCancel: false
    //             })
    //           }
    //         }
    //       })
    //     } else {
    //       that.submit(e)
    //     }
    //   }
    // })
  },



  submit: function(e) {
    var user = app.globalData.userInfo;
    var that = this
    app.getUserOpenId(function(err, skey) {
      if (!err && that.data.gift_id > 0) {

        //获取发出的数据
        wx.showLoading({
          title: '加载中'
        });
        wx.request({
          url: app.globalData.domain + '/api/vote/sendgift',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            theme_id: theme_id,
            actor_id: actor_id,
            nickname: encodeURI(user.nickName),
            avatarurl: user.avatarUrl,
            gift_id: that.data.gift_id,
            gift_name: that.data.gift_name,
            pay: that.data.price,
            vote: that.data.vote,
            skey: skey
          },
          success: function(res) {

            console.log(that.data.price)

            wx.hideLoading()
            console.log(res)
            if (res.data.code > 0) {
              //判断code
              if (res.data.code == 2) {
                var payargs = res.data.payargs

                console.log(78345345);
                //调起微信支付
                wx.requestPayment({
                  'timeStamp': payargs.timeStamp,
                  'nonceStr': payargs.nonceStr,
                  'package': payargs.package,
                  'signType': payargs.signType,
                  'paySign': payargs.paySign,
                  'success': function(res) {
                    that.successAlert()
                  }, //支付成功
                  'fail': function(res) {},
                  'complete': function(res) {}
                })
              } else {
                that.successAlert()
              }
            } else {
              if (res.data.code == -2) {
                wx.showModal({
                  title: '温馨提示',
                  content: '24小时之内，只能免费投票一次',
                  showCancel: false
                })
              } else {
                wx.showToast({
                  title: '支持失败'
                });
              }
            }
          },
          fail: function(res) {
            wx.showToast({
              title: '数据加载失败',
              image: '../images/cuowu.png'
            });
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
  successAlert: function() {
    wx.showModal({
      title: '投票成功',
      content: '谢谢支持',
      showCancel: false,
      success: function() {
        wx.redirectTo({
          url: '../detail/detail?actor_id=' + actor_id + '&theme_id=' + theme_id,
        })
      }
    })


  },

  chooseGift: function(e) {
    var that = this;
    console.log(e)
    var index = e.currentTarget.dataset.index
    that.setData({
      selected: index,
      price: e.currentTarget.dataset.price,
      gift_id: that.data.gifts[index].id,
      gift_name: that.data.gifts[index].name,
      vote: that.data.gifts[index].vote,
    })
    if (that.data.price > 0) {
      that.setData({
        'submitText': '微信支付'
      })
    } else {
      that.setData({
        'submitText': '支持一下'
      })
    }
  },
  //用户点击右上角分享
  onShareAppMessage: function() {

  }
})