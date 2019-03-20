
const app = getApp();

var times;
var exam_id;
var i = 0;
var j = 0;
var exam;

var isfirst = true;
var stand_time;
var score = 0;
const innerAudioContext = wx.createInnerAudioContext();

var start; //答题开始时间
var end; //答题结束时间
var total_time = 0; //答题总用时
var rights = 0; //答对题数
var wrongs = 0; //答错题数
var detail = [];
var countTimer = null;// 设置 定时器 初始为null;
var fontTimer = null;
var fontTimer2 = null;
var event;


Page({
  data: {
    time: '',
    num: 0,
    options: [],
    _state: 0,
    score: 0, //答题总成绩
    total_time: 0,//总答题用时

    // 加载页面 答题页面 结果页面 （显示参数）
    load_display: true,
    answer_display: false,
    result_display: false,
    error_display: false,
    domain: app.globalData.domain,
    viewstyle: [],
    record_id: 0
  },

  // 时间减少
  countInterval: function () {
    this.countTimer = setInterval(() => {
      if (this.data.num >= 0) {
        if ((/(^[1-9]\d*$)/.test(this.data.num / 10))) {
          this.setData({
            time: this.data.num / 10
          });
        }
      } else {
        this.setData({
          time: 0
        });
        if (exam.timer == 1) {
          this.answerSub(0);
          innerAudioContext.src = "http://dati.webimage.cn/static/xcx/wrong.mp3";
          innerAudioContext.play();
        }
      }
      this.data.num--;
    }, 100)
  },

  // 加载页面倒计时
  numInterval: function (num) {
    let that = this;
    innerAudioContext.src = "http://dati.webimage.cn/static/xcx/readygo.mp3";
    innerAudioContext.play();
    this.fontTimer = setInterval(() => {
      if (num > 0) {
        that.setData({
          font_num: num,
          font_state: "countdown_small"
        })
        if (num != 1) {
          this.fontTimer2 = setTimeout(() => {
            that.setData({
              font_state: "countdown_big"
            })
          }, 980)
        }
        num--
      } else {
        that.setData({
          load_display: false,
          answer_display: true,
          result_display: false,
        })
        that.resetExam(0);
        clearInterval(this.fontTimer);
        clearTimeout(this.fontTimer2);
      }
    }, 1000)
  },





  //页面卸载

  onUnload: function () {

    let that = this;

    if (that.data.pagenum < that.data.pagecount) {
      that.submit()
    }

    clearInterval(this.fontTimer);
    clearInterval(this.countTimer);
    clearTimeout(this.fontTimer2);
    clearTimeout(this.waitTimer);
    i = 0;
    rights = 0; //答对题数
    wrongs = 0;
    score = 0;
    total_time = 0;
    detail = [];
    that.setData({
      score: score,
      total_time: 0,
      load_display: false,
      answer_display: true,
      result_display: false,
    })
  },

  //页面加载 
  onLoad: function (e) {
    let that = this;
    exam_id = e.exam_id
    times = e.times
    event = e
    i = 0 //页面重新加载时，刷新题目，i重置为0
    rights = 0; //答对题数
    wrongs = 0;
    score = 0;
    total_time = 0;
    detail = [];
    that.setData({
      exam_id: exam_id,
      user: app.globalData.user,
      times: times,
      score: score,
      total_time: 0,
      _state: 0,
      load_display: true,
      answer_display: false,
      result_display: false
    })
    that.getData()
  },

  // 再答一次
  restart: function () {
    let that = this;
    that.onLoad(event);
  },

  // 答题
  answerSub: function (event) {
    let that = this;
    clearInterval(this.countTimer);
    end = new Date().getTime();
    var use_time = end - start;

    var option_id = (event == 0) ? 0 : event.currentTarget.dataset.id;
    var is_right = (event == 0) ? 0 : event.currentTarget.dataset.isright;
    var _index = (event == 0) ? -1 : event.currentTarget.dataset.index;


    if (is_right) {
      var average_score = 100 / Number(this.data.pagecount);
      if (exam.timer == 0) {
        var subject_score = average_score;
      } else {

        var residue_time = stand_time * 100 - use_time;
        if (exam.timer == 1 && residue_time < 0) {
          var subject_score = 0;//超时判错
        } else {
          var subject_score = average_score + residue_time * 0.0001;
        }
      }
      innerAudioContext.src = "http://dati.webimage.cn/static/xcx/right.mp3";
      innerAudioContext.play();
      if (subject_score < 0) { subject_score = 0.01 };


      rights++;
    } else {
      innerAudioContext.src = "http://dati.webimage.cn/static/xcx/wrong.mp3";
      innerAudioContext.play();
      var subject_score = 0;
      wrongs++;
    }


    score += subject_score;
    total_time += use_time;

    detail.push({ "option_id": option_id, "is_right": is_right, "score": Math.round(subject_score * 100) / 100, "time": use_time });

    this.setData({
      _state: 1,
      _index: _index,
      score: Math.round(score * 100) / 100,
      total_time: parseInt(total_time / 1000),
      accuracy: Math.round(rights / this.data.pagecount * 100),

    })

    i++;
    if (this.data.pagenum == this.data.pagecount) {
      this.waitTimer = setTimeout(() => { that.submit() }, 1000);
    } else {
      this.waitTimer = setTimeout(() => { that.resetExam(i) }, 1000);
    }

  },

  // 答题点击时触发事件
  onSelected: function (e) {
    if (this.data._state == 0) {
      this.answerSub(e);
    }

  },

  //显示图片
  showImage: function () {
    let that = this;
    wx.previewImage({
      urls: [app.globalData.domain + '/' + that.data.subject_photo] // 需要预览的图片http链接列表
    })

  },



  // 刷新题目 
  resetExam: function (i) {
    let that = this;
    clearTimeout(this.waitTimer);
    if (exam.subjects.length) {
      stand_time = exam.subjects[i].timelimit * 10;
      that.setData({
        _state: 0,
        subject_title: exam.subjects[i].title,
        subject_photo: exam.subjects[i].photo,
        options: exam.subjects[i].options,
        num: (exam.subjects[i].timelimit) * 10,
        pagenum: i + 1,
        pagecount: exam.subjects.length,
      });
      //选项字体样式
      for (var i = 0; i < this.data.options.length; i++) {
        var answer_length = this.data.options[i].answer.length
        var answer_style = "middle"
        if (answer_length > 10) {
          answer_style = "small"
        }
        if (answer_length <= 8) {
          answer_style = "big"
        }
        that.setData({
          ['options[' + i + '].viewstyle']: answer_style
        })
      }
      that.countInterval();

      start = new Date().getTime();

    } else {
      that.setData({
        answer_display: false,
        error_display: true
      })
    }

  },


  //获取数据
  getData: function () {
    var that = this
    wx.request({
      url: app.globalData.domain + '/api/subject/sel', 
      method: 'POST',
      data: {
        exam_id: exam_id
      },
      success: function (res) {
        if (res.data.code > 0) {
          exam = res.data.data
          that.setData({
            exam: exam
          })
          that.numInterval(4);
        }
        else {
          wx.showToast({ title: '暂无试卷' });
        }
      },
      fail: function (res) {
        wx.showToast({ title: '数据加载失败' });
      }
    })
  },

  // 提交函数 

  submit: function () {
    let that = this;
    that.setData({
      answer_display: false,
      result_display: true,
    })
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        wx.request({
          url: app.globalData.domain + '/api/record/add',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            "exam_id": exam.id,
            "skey": skey,
            "nickname": encodeURI(app.globalData.user.nickName),
            "avatarurl": app.globalData.user.avatarUrl,
            "score": Math.round(score * 100, 0),
            "totaltime": total_time,
            "rights": rights,
            "wrongs": wrongs,
            "detail": JSON.stringify(detail)
          },
          success: function (res) {
            if (res.data.code > 0) {
              that.setData({
                success_data: res.data.data,
                record_id: res.data.data.record_id
              })

              if (res.data.data.exam_times > 0) {
                app.globalData.own_times = res.data.data.times
              }
              if (res.data.data.exam_times < 0) {
                app.globalData.own_times = res.data.data.today_times
              }
              that.setData({
                own_times: app.globalData.own_times
              })
            }
          }
        })
      } else {
      }
    })
  },


  //用户分享
  onShareAppMessage: function () {
    return {
      title: exam.title,
      path: '/pages/answer/answer?exam_id=' + exam_id,
    }
  }
})