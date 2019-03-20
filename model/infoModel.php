<?php
namespace MODEL;


class InfoModel extends Model {

    public function getNews(int $titleCount, int $bodyCount, int $limit, string $webDomain, int $internal) : array {
        $db = new \DATABASE\Database();

        $data = $db->query("SELECT SUBSTRING(news.title, 1, :titleCount) title, SUBSTRING(news.description, 1, :bodyCount) description, news.author, news.updated FROM website_news news WHERE internal = :internal AND (SELECT web.id FROM company_web_domains web WHERE web.name = :web_domain) = news.web_domain ORDER BY id DESC LIMIT :limit", ["titleCount" => $titleCount, "bodyCount" => $bodyCount, "internal" => $internal, "web_domain" => $webDomain, "limit" => $limit])
            ->fetchArray();

        return $data;
    }

    public function getAbout(string $webDomain) : array {
        $db = new \DATABASE\Database();

        $data = $db->query("SELECT about.description, about.author, about.updated FROM website_about about WHERE (SELECT id FROM company_web_domains web WHERE web.name = :web_domain) = about.web_domain LIMIT 1", ["web_domain" => $webDomain])
            ->fetchArray();

        return $data;
    }

}