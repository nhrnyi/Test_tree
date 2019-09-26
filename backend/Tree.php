<?php
// For simplicity, instead namespace
include __DIR__.'\..\system\DB.php';

/**
 * Class Tree
 * Simplified tree
 */
class Tree
{
    /**
     * @param array $data [parent_id, name]
     * @return int - new node id
     */
    public function addNode(array $data) :int
    {
        $sth = DB::run()
            ->prepare("
                    INSERT INTO `tree` (`parent_id`, `name`)
                    VALUES (:id, :name)
                ");
        $sth->execute([
            ':parent_id' => $data['parent_id'],
            ':name' => $data['name']
        ]);

        return DB::run()->lastInsertId();
    }

    /**
     * @param array $data [id]
     * @return bool
     */
    public function removeNode(array $data) :bool
    {
        $sth = DB::run()
            ->prepare("
                    DELETE FROM `tree`
                    WHERE `id` = :id
                    LIMIT 1
                ");
        $sth->execute([
            ':id' => $data['id']
        ]);

        return true;
    }

    /**
     * @param array $data [id, name - new name]
     * @return bool
     */
    public function updateNode(array $data) :bool
    {
        $sth = DB::run()
            ->prepare("
                    UPDATE `tree`
                    SET `name` = :name
                    WHERE `id` = :id
                    LIMIT 1
                ");
        $sth->execute([
            ':id' => $data['id'],
            ':name' => $data['name']
        ]);

        return true;
    }

    /**
     * Return node by id
     * @param array $data [id]
     * @return array
     */
    public function getNode(array $data) :array
    {
        $sth = DB::run()
            ->prepare("
                    SELECT 
                        `t`.`id`,
                        `t`.`parent_id`,
                        `t`.`name`
                    FROM 
                        `tree` `t`
                    WHERE
                        `t`.`id` = :id
                    LIMIT 1
                ");
        $sth->execute([':id' => $data['id']]);

        $res = $sth->fetch(PDO::FETCH_ASSOC);

        return $res ?? [];
    }

    /**
     * Return all node of tree
     * @return array
     */
    public function getAll() :array
    {
        $sth = DB::run()
            ->prepare("
                    SELECT 
                        `t`.`id`,
                        `t`.`parent_id`,
                        `t`.`name`
                    FROM 
                        `tree` `t`
                ");
        $sth->execute();

        $res = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $res ?? [];
    }
}