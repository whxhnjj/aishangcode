
import { Core } from '../../extend/core.js';
var core = new Core();
var timer;

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    goods: [],
    page: 1,
    isReachBottom: false,
    page_text: '暂无更多商品',
    swiper: []
  },

  onLoad: function (e) {
    let that = this;
    // that.getShopInfo();
    // that.getSwiper();
    // that.getNotice();
    that.setData({ page: 1, goods: [], isReachBottom: false });
    // that.getGoodsList(that.data.page);
    that.getHome();
  },

  onHide: function () {
    clearInterval(timer);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    clearInterval(timer);
    that.setData({
      page: 1,
      page_text: '正在加载',
      goods: [],
      isReachBottom: false
    });
    that.getHome();
    // that.getGoodsList(that.data.page);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      clearInterval(timer);
      that.getGoodsList(that.data.page);
    }
  },


  //获取首页数据
  getHome: function () {
    let that = this;
    core.request({
      url: 'api/info/home',
      method: 'GET',
      data: {
        limit: 3
      },
      success: function (res) {
        that.setData({
          info: res.data.info,
          notice: res.data.notice,
          swiper: res.data.swiper
        });

        // 
        var goods = that.data.goods;
        if (res.data.goods.length < 3) {
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);
            goods.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多商品',
            goods: goods
          });
        } else {
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);
            goods.push(list);
          }
          that.setData({
            page: 2,
            page_text: '加载更多',
            goods: goods
          });
        }

        if (goods.length > 0) {
          timer = setInterval(function () {
            that.countDown(goods);
          }, 1000);
        }
        //

      },
      fail: function (res) {
      }
    });

  },

  //请求推荐商品列表
  getGoodsList: function (page) {
    let that = this;
    core.request({
      url: 'api/goods/lists',
      method: 'GET',
      data: {
        is_recommend: 1,
        page: page,
        limit: 3
      },
      success: function (res) {

        var goods = that.data.goods;
        if (res.data.goods.length < 3) {
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);
            goods.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多商品',
            goods: goods
          });
        } else {
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);
            goods.push(list);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            goods: goods
          });
        }

        if (goods.length > 0) {
          timer = setInterval(function () {
            that.countDown(goods);
          }, 1000);
        }

      },
      fail: function (res) {
      }
    });
  },

  //倒计时
  countDown: function (goods) {
    let that = this;
    // console.log(goods);
    var begin_time, end_time, now_time, add_time, time;

    for (let i = 0; i < goods.length; i++) {
      if (goods[i].begin_time == 0 || goods[i].end_time == 0 || goods[i].store_count == 0) {
        goods[i].down_state = 0;
      } else {
        goods[i].down_state = 1;
        goods[i].time_title = '倒计时开始等待';
        goods[i].time_day = '00';
        goods[i].time_h = '00';
        goods[i].time_m = '00';
        goods[i].time_s = '00';

        begin_time = goods[i].begin_time * 1000;
        end_time = goods[i].end_time * 1000;
        now_time = new Date().getTime();
        time;

        if (end_time < now_time) {
          goods[i].down_state = 0;
        } else {
          if (begin_time <= now_time && now_time <= end_time) {
            goods[i].time_title = '距结束剩';
            time = new Date(end_time - now_time);
          } else {
            goods[i].time_title = '距开始剩';
            time = new Date(begin_time - now_time);
          }

          goods[i].time_day = parseInt(time / (1000 * 60 * 60 * 24));
          goods[i].time_h = parseInt((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          goods[i].time_m = parseInt((time % (1000 * 60 * 60)) / (1000 * 60));
          goods[i].time_s = parseInt((time % (1000 * 60)) / 1000);
          if (goods[i].time_day < 10) {
            goods[i].time_day = '0' + goods[i].time_day;
          }
          if (goods[i].time_h < 10) {
            goods[i].time_h = '0' + goods[i].time_h;
          }
          if (goods[i].time_m < 10) {
            goods[i].time_m = '0' + goods[i].time_m;
          }
          if (goods[i].time_s < 10) {
            goods[i].time_s = '0' + goods[i].time_s;
          }

        }

        /*五天显示倒计时
        if (begin_time - now_time <= 5 * 24 * 60 * 60 * 1000 && begin_time - now_time > 0 || end_time - now_time <= 5 * 24 * 60 * 60 * 1000 && end_time - now_time > 0) {
          goods[i].time_state = 1;
        }*/

        //10小时显示倒计时
        if (end_time - now_time <= 5 / 12 * 24 * 60 * 60 * 1000 && end_time - now_time > 0) {
          goods[i].time_state = 1;
        }

      }

      //标签
      if (goods[i].begin_time != 0 || goods[i].end_time != 0) {
        add_time = goods[i].add_time * 1000;
        begin_time = goods[i].begin_time * 1000;
        end_time = goods[i].end_time * 1000;
        now_time = new Date().getTime();
        if (now_time < begin_time) { goods[i].class_state = 0; } //即将上线
        if (now_time - begin_time < 3 * 24 * 60 * 60 * 1000 && now_time - begin_time > 0) {
          goods[i].class_state = 1; //新品
        }
        if (now_time > end_time) { goods[i].class_state = 3; }//已结束
      }
    }
    that.setData({
      goods: goods
    })

  },

  //获取店铺信息
  getShopInfo: function () {
    let that = this;
    core.request({
      url: 'api/info/system',
      method: 'GET',
      success: function (res) {
        that.setData({
          info: res.data.info
        });
      },
      fail: function (res) {
      }
    });
  },

  //获取公告
  getNotice: function () {
    let that = this;
    core.request({
      url: 'api/info/notice',
      method: 'GET',
      success: function (res) {
        that.setData({
          notice: res.data.notice
        });
      },
      fail: function (res) {
      }
    });
  },

  //获取轮播
  getSwiper: function () {
    let that = this;
    core.request({
      url: 'api/info/swiper',
      method: 'GET',
      success: function (res) {
        that.setData({
          swiper: res.data.swiper
        })
      },
      fail: function (res) {
      }
    });
  },

  //轮播页面跳转
  swiperLink: function (e) {
    let that = this;
    var num = e.target.id;
    var swiper = that.data.swiper;
    if (swiper[num].type == 1) {
      wx.navigateTo({
        url: '../goods_detail/goods_detail?id=' + swiper[num].goods_id
      });

    } else if (swiper[num].type == 2) {
      app.globalData.cate_id = swiper[num].cate_id;
      wx.switchTab({
        url: '../category/category'
      });
    } else {
      //无关联
    }
  },

  onShareAppMessage: function () {
    let that = this;
    return {
      path: '/pages/index/index?scene=s-' + app.globalData.member.id
    }
  }

})