export interface AdminJwtPayloadType {
  level: number | null;
  id: number;
  name: string;
  iat?: number;
  exp?: number;
}
