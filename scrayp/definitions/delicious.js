{
  "name": "Delicious bookmarks",
  
  "url": "http://delicious.com/url/%s?show=all&page=%d",  
  "root": ".post",
  
  "properties": {
    "dc:identifier": ["", ["attribute", "id"]],
    "dc:date": [".bookmark .dateGroup span", ["attribute", "title"], "var d = Date.parseDate(text, 'd M y'); if (d) return d.getTime(); else return text;"],
    "dc:description": [".bookmark .description", "text"]
  }
}
