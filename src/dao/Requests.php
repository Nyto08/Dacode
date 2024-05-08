<?php
namespace dacode\dao;

class Requests {
    // Charge tout sauf le code (qui est chargé au dernier moment car potentiellement lourd)
    public const SELECT_PLAYG_WORKSPACES_BY_USER_ID = "select WkPlayg.id_wk, slot_idx_wk, name_wk, workspace.crea_wk, workspace.modif_wk from WkPlayg inner join workspace on WkPlayg.id_wk = workspace.id_wk where id_user = ? order by slot_idx_wk asc";
    public const SELECT_PLAYG_WORKSPACE_BY_USER_ID_AND_SLOT_NO_CODE = "select workspace.id_wk, id_user, crea_wk, modif_wk, slot_idx_wk, name_wk from workspace inner join WkPlayg on workspace.id_wk = WkPlayg.id_wk where id_user = :user_id and slot_idx_wk = :slot_idx";
    public const SELECT_PLAYG_WORKSPACE_BY_USER_ID_AND_SLOT_WITH_CODE = "select workspace.id_wk, id_user, crea_wk, modif_wk, slot_idx_wk, name_wk, id_cod, data_cod, code.id_lang, name_lang, editor_lang from workspace inner join WkPlayg on workspace.id_wk = WkPlayg.id_wk inner join Code on workspace.id_wk = code.id_wk inner join Langage on code.id_lang = langage.id_lang where id_user = :user_id and slot_idx_wk = :slot_idx;";
    public const FUNC_CREATE_PLAYG_WORKSPACE = "select create_playg_workspace(:user_id, :name_wk, :slot_idx) as id_wk";
    public const DEL_PLAYG_WORKSPACE = "delete from WkPlayg where id_wk = :id_wk";
    public const SELECT_CODE_FROM_PLAYG_SLOT = "select code.id_lang, code.id_wk, data_cod, name_lang, editor_lang, slot_idx_wk, id_user from code inner join workspace on code.id_wk = workspace.id_wk inner join WkPlayg on code.id_wk = WkPlayg.id_wk inner join langage on code.id_lang = langage.id_lang where slot_idx_wk = :slot_idx and id_user = :user_id";
    public const INSERT_CODE_IN_WORKSPACE = "insert into code (id_wk, id_lang, data_cod) values
    (:id_wk, :id_lang, :data_code)";
    public const DELETE_CODE_BY_WORKSPACE_ID = "delete from code where id_wk = :id_wk";
    public const UPDATE_PLAYG_WORKSPACE_NAME = "update WkPlayg set name_wk = :name_wk where id_wk = :id_wk";
    public const ADD_USER_ACCOUNT = "insert into userprofile (id_sub, pwd_user, role_user, mail_user, pseudo_user) values (:id_sub, :pwd_user, :role_user, :mail_user, :pseudo_user)";
    public const DELETE_USER_BY_PSEUDO = "delete from userprofile where pseudo_user = :pseudo_user";
    public const SELECT_USER_BY_PSEUDO = "select id_user, userprofile.id_sub, pwd_user, role_user, crea_date, mail_user, pseudo_user, date_sub, name_sub, type_sub from userprofile inner join subscription on userprofile.id_sub = subscription.id_sub where pseudo_user = ?";
    public const SELECT_USER_BY_ID = "select id_user, userprofile.id_sub, pwd_user, role_user, crea_date, mail_user, pseudo_user, date_sub, name_sub, type_sub from userprofile inner join subscription on userprofile.id_sub = subscription.id_sub where id_user = ?";
    public const VERIFY_USER_BY_PSEUDO = "select id_user from userprofile where pseudo_user = ?";
    public const VERIFY_USER_BY_MAIL = "select id_user from userprofile where mail_user = ?";
    public const SELECT_LANGAGE_BY_NAME = "select id_lang, name_lang, editor_lang from langage where name_lang = :name_lang";
    public const SELECT_LANGAGES = "select id_lang, name_lang, editor_lang from langage";
    public const SELECT_LANGAGE_BY_ID = "select id_lang, name_lang, editor_lang from langage where id_lang = :id_lang";
    public const INSERT_LANGAGE = "insert into langage (id_lang, name_lang, editor_lang) values (:id_lang, :name_lang,:editor_lang)";
    public const DELETE_LANGAGE = "delete from langage where id_lang = :id_lang";
    public const UPDATE_LANGAGE = "update langage set name_lang = :name_lang, editor_lang = :editor_lang where id_lang = :id_lang";
}