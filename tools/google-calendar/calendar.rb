# coding: utf-8
require 'google/api_client'
require 'net/http'

config = eval File.read 'config.rb'

client = Google::APIClient.new(:application_name => '')

# 認証
key = Google::APIClient::KeyUtils.load_from_pkcs12(config[:private_key], config[:private_pass])
client.authorization = Signet::OAuth2::Client.new(
  :token_credential_uri => 'https://accounts.google.com/o/oauth2/token',
  :audience => 'https://accounts.google.com/o/oauth2/token',
  :scope => 'https://www.googleapis.com/auth/calendar.readonly',
  :issuer => config[:api_email],
  :signing_key => key)
client.authorization.fetch_access_token!

# イベント取得
cal = client.discovered_api('calendar', 'v3')

now = Time.new
time_min = Time.utc(now.year, now.mon, now.day, 0, 0, 0).iso8601
time_max = Time.utc(now.year, now.mon, now.day, 23, 59, 59).iso8601
params = {'calendarId' => config[:calendarId],
          'orderBy' => 'startTime',
          'timeMax' => time_max,
          'timeMin' => time_min,
          'singleEvents' => 'True'}

result = client.execute(:api_method => cal.events.list,
                        :parameters => params)

# イベント格納
events = []
result.data.items.each do |item|
  events << item
end

# 出力
events.each do |event|
  begin 
    now = Time.new
    start = event.start.dateTime
    remind = start - 5 * 60

    text = "【5分前】 #{event.summary} <#{event.htmlLink}|link>"

    args = {
      'year' => remind.year,
      'mon' => remind.mon,
      'day' => remind.day,
      'hour' => remind.hour,
      'min' => remind.min,
      'text' => text,
    }

    if now < event.start.dateTime
      res = Net::HTTP.post_form(URI.parse(config[:post_url]), args)
    end
  rescue
  end
end
