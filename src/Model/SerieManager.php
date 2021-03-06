<?php
/**
 * Created by PhpStorm.
 * User: aragorn
 * Date: 03/04/18
 * Time: 17:14
 */

namespace Model;

class SerieManager extends AbstractManager
{
    const TABLE = 'serie';

    /**
     * SerieManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    public function selectByPage(int $page, int $limit)
    {
        return $this->pdoConnection->query(
            'SELECT * FROM ' . $this->table . ' ORDER BY ' . "$this->table.title" . ' LIMIT ' . $limit . ' OFFSET ' . ($page - 1) * $limit,
            \PDO::FETCH_CLASS,
            $this->className
        )->fetchAll();
    }

    public function recupPageMax()
    {
        $limit = 12;

        $data = $this->pdoConnection->query('SELECT COUNT(*) AS total FROM ' . $this->table)->fetch(\PDO::FETCH_ASSOC);
        $total = $data['total'];

        $pageMax = ceil($total / $limit);

        return $pageMax;
    }

    /**
     * @param array $file
     * @return null|string
     * @throws \Exception
     */
    public function upload(array $file)
    {
        $uploadDir = 'assets/upload/';
        $maxsize  = 1048576;
        $acceptable = [
            'jpg',
            'jpeg',
            'gif',
            'png'
        ];

        if (!empty($_POST)) {
            for ($i = 0; $i < count($file["name"]); $i++) {
                if ($file["name"][0] === "") {
                    $filePath = null;
                } else {
                    $fileName = $file["tmp_name"][$i];
                    $extension_upload = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
                    $uniqueSaveName = uniqid();
                    $filePath = $uniqueSaveName . '.' . $extension_upload;

                    if (($file[$i] >= $maxsize) || ($file['size'][$i] == 0)) {
                        throw new \Exception('File too large. File must be less than '.$maxsize .' bytes.');
                    }

                    if (!in_array($extension_upload, $acceptable) && !empty($file['type'][$i])) {
                        throw new \Exception('Invalid file type. Only '.implode(',', $acceptable).' types are accepted.');
                    }
                    move_uploaded_file($fileName, $uploadDir.$filePath);
                }
            }
        }
        return $filePath;
    }


    /**
     * @param $searchterm
     * @return array
     */
    public function searchBar($searchterm)
    {
        if (!empty($searchterm)) {
            $req = $this->pdoConnection->prepare("SELECT * FROM serie WHERE title LIKE :searchterm");
            $req->bindValue(':searchterm', $searchterm, \PDO::PARAM_STR);
            $req->execute(array('searchterm' => '%' . $searchterm . '%'));
            $result = $req->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }
    }

    public function averageNote($idserie)
    {
        $average = $this->pdoConnection->prepare("SELECT AVG(note) AS avgNote FROM episode WHERE idserie= :idserie");
        $average->setFetchMode(\PDO::PARAM_INT);
        $average->bindValue('idserie', $idserie);
        $average->execute();
        return $average->fetch();
    }
}
